<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/5/7
 * Time: 9:42 PM
 */

namespace common\services;


use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\consts\SystemConst;
use common\exceptions\DefaultException;
use common\models\Order;
use common\models\User;
use EasyWeChat\Factory;
use Yii;
use yii\base\Exception;

class OrderService
{
    /**
     * @param User $user
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \common\exceptions\DefaultException
     */
    public function book(User $user)
    {
        $order = (new Order())->findByUserId($user->user_id);
        if ($order) {
            if (!$order->isFinalStatus()) throw new DefaultException(ErrorConst::ERROR_ORDER_PAYING);
            if ($order->isSucc()) throw new DefaultException(ErrorConst::ERROR_ORDER_DONE);
        }
        $order = (new Order())->addOne($user->user_id, 1, '课程购买');
        return $this->handleBook($user, $order);
    }

    /**
     * @param $orderNo
     * @return bool
     * @throws DefaultException
     */
    public function fail($orderNo)
    {
        $order = (new Order())->findOrThrow($orderNo);
        if ($order->isFinalStatus()) return true;
        $order->setFail();
        return true;
    }

    private function handleBook(User $user, Order $order)
    {
        $app = Factory::payment(Yii::$app->params[SystemConst::PARAMS_CONFIG_MINI_PROGRAM]);
        $result = $app->order->unify([
            'body' => $order->order_desc,
            'out_trade_no' => $order->order_no,
            'total_fee' => $order->orderAmount,
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $user->user_openid,
        ]);
        Log::info("支付申请结果", $result, LogTypeConst::TYPE_ORDER);
        Log::info("支付申请结果", $order->orderAmount, LogTypeConst::TYPE_ORDER);
        $time = (string) time();
        $str = "appId=wxfc3abbaf412150d2&nonceStr={$result['nonce_str']}&package=prepay_id={$result['prepay_id']}&signType=MD5&timeStamp={$time}&key=b0baee9d279d34fa1dfd71aadb908c3f";

        return [
            'timeStamp' => $time,
            'nonceStr' => $result['nonce_str'],
            'package' => "prepay_id=" . $result['prepay_id'],
            'paySign' => strtoupper(md5($str)),
            'orderNo' => $order->order_no,
        ];
    }

    public function notify()
    {
        $app = Factory::payment(Yii::$app->params[SystemConst::PARAMS_CONFIG_MINI_PROGRAM]);
        $response = $app->handlePaidNotify(function($message, $fail){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = (new Order())->findByOrderNo($message['out_trade_no']);
            Log::info("支付结果通知", [
                'message' => $message,
            ], LogTypeConst::TYPE_ORDER);
            if (!$order) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                Log::info("支付结果同志1", [
                    'message' => $message,
                ], LogTypeConst::TYPE_ORDER);
                $tran = Yii::$app->db->beginTransaction();
                try {
                    // 用户是否支付成功
                    if ($message['result_code'] === 'SUCCESS') {
                        $order->setSucc();
                        $order->user->setPaid();
                        // 用户支付失败
                    } elseif ($message['result_code'] === 'FAIL') {
                        $order->setFail();
                    }
                    $tran->commit();
                    Log::info("支付结果更新成功", [
                        'message' => $message,
                    ], LogTypeConst::TYPE_ORDER);
                } catch (Exception $e) {
                    $tran->rollBack();
                    Log::error("支付结果更新失败", [
                        'message' => $message,
                        'error_msg' => $e->getMessage(),
                    ], LogTypeConst::TYPE_ORDER);
                    return $fail('通信失败，请稍后再通知我');
                }
            } else {
                Log::info("支付结果同志2", [
                    'message' => $message,
                ], LogTypeConst::TYPE_ORDER);
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        $response->send(); // return $response;
    }
}