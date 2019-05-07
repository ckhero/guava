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
        $app = Factory::payment(Yii::$app->params[SystemConst::PARAMS_CONFIG_MINI_PROGRAM]);
        $order = (new Order())->findByUserId($user->user_id);
        if ($order) {
            if (!$order->isFinalStatus()) throw new DefaultException(ErrorConst::ERROR_ORDER_PAYING);
            if ($order->isSucc()) throw new DefaultException(ErrorConst::ERROR_ORDER_DONE);
        }
        $order = (new Order())->addOne($user->user_id, 1, '课程购买');
        $result = $app->order->unify([
            'body' => $order->order_desc,
            'out_trade_no' => $order->order_no,
            'total_fee' => $order->order_amount,
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => $user->user_openid,
        ]);
        Log::info("支付结果", $result, LogTypeConst::TYPE_ORDER);
        return [
            'timeStamp' => (string) time(),
            'nonceStr' => $result['nonce_str'],
            'package' => $result['prepay_id'],
            'paySign' => $result['sign'],
        ];
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
}