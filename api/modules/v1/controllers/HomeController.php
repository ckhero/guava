<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/19
 * Time: 11:18 PM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\models\User;
use common\services\HomeService;

class HomeController extends ApiController
{
    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionIndex()
    {
        $user = (new User())->checkLogin();

        $res = (new HomeService())->index($user);
        return Format::success($res);
    }
}