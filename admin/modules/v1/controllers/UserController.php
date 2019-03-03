<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 12:21 AM
 */

namespace admin\modules\v1\controllers;


use admin\services\UserService;
use common\components\Format;

class UserController extends AdminController
{
    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $name = $this->getParam('name');
        $email = $this->getParam('email');
        $password = $this->getParam('password');
        $roles = $this->getParam('roles', []);
        $userId = $this->getParam('user_id', 0);

        $res = (new UserService($this->adminUser))->create($name, $email, $password, $roles, $userId);
        return Format::success($res);
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        $name = $this->getParam('username');
        $password = $this->getParam('password');

        $res = (new UserService())->login($name, $password);
        return Format::success($res);
    }

    public function actionList()
    {
        $currPage = $this->getParam('curr_page', 1);
        $pageSize = $this->getParam('page_size', 20);

        $res = (new UserService())->list($currPage, $pageSize);
        return Format::success($res);
    }
}