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
use common\consts\SystemConst;
use common\services\OrderService;
use EasyWeChat\Factory;
use Yii;

class OrderController extends ApiController
{
    public function actionNotify()
    {
        $app = Factory::payment(Yii::$app->params[SystemConst::PARAMS_CONFIG_MINI_PROGRAM]);
    }

    public function actionBook()
    {
        $lessonId = $this->getParam('id');
        $user = (new User())->checkLogin();
        $res = (new OrderService())->book($user);
        return Format::success($res);
    }
}