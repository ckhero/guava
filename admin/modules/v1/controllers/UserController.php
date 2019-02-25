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
        $adminUserName = $this->getParam('name');
        $adminUserEmail = $this->getParam('email');
        $adminUserPassword = $this->getParam('password');
        $res = (new UserService($this->adminUser))->create($adminUserName, $adminUserEmail, $adminUserPassword);
        return Format::success($res);
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        $email = $this->getParam('email');
        $password = $this->getParam('password');

        $res = (new UserService())->login($email, $password);
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