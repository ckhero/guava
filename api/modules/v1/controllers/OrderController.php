<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/5/7
 * Time: 9:22 PM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\components\Log;
use common\consts\LogTypeConst;
use common\consts\SystemConst;
use common\models\User;
use common\services\OrderService;
use EasyWeChat\Factory;
use Yii;

class OrderController extends ApiController
{
    public function actionNotify()
    {
        return (new OrderService())->notify();
    }

    /**
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \common\exceptions\DefaultException
     */
    public function actionBook()
    {
        $lessonId = $this->getParam('id');
        $user = (new User())->checkLogin();
        $res = (new OrderService())->book($user);
        return Format::success($res);
    }

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionFail()
    {
        $orderNo = $this->getParam('order_no');
        $user = (new User())->checkLogin();
        $res = (new OrderService())->fail($orderNo);
        return Format::success($res);
    }
}