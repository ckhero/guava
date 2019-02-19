<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 10:28 AM
 */

namespace api\modules\v1\controllers;


use common\services\UserService;
use common\components\ApiController;
use common\components\Format;
use common\models\User;
use Yii;

class UserController extends ApiController
{
    /**
     * 登陆接口
     * @return array
     * @throws \common\exceptions\DefaultException
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        $openid = $this->getParam('openid');

        $res = (new UserService())->login($openid);

        return Format::success($res);
    }

    /**
     * 用户信息获取
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionInfo()
    {
        $user = (new User())->checkLogin();

        $res = (new UserService())->info($user);

        return Format::success($res);
    }
}