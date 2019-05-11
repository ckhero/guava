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
        $code = $this->getParam('code');
        $iv = $this->getParam('iv');
        $encryptData = $this->getParam('encrypt_data');

        $res = (new UserService())->login($code, $iv, $encryptData);

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

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionSetPhone()
    {
        $user = (new User())->checkLogin(false);
        $code = $this->getParam('code');
        $iv = $this->getParam('iv');
        $encryptData = $this->getParam('encrypt_data');

        $res = (new UserService())->setPhone($user, $code, $iv, $encryptData);

        return Format::success($res);
    }

    public function actionSetSessionKey()
    {
        $user = (new User())->checkLogin(false);
        $code = $this->getParam('code');
        $res = (new UserService())->setSessionKey($user, $code);

        return Format::success($res);
    }
}