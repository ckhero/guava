<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 3:52 PM
 */

namespace admin\modules\v1\controllers;


use admin\services\PtUserService;
use common\components\Format;

class PtUserController extends AdminController
{
    public function actionList()
    {
        $userPayStatus = $this->getParam('user_pay_status');
        $userId = $this->getParam('user_id');
        $userName = $this->getParam('user_name');
        $userPhone = $this->getParam('user_phone');
        $page = $this->getParam('page', 1);
        $limit = $this->getParam('limit', 20);

        $res = (new PtUserService())->list($userPayStatus, $userId, $userName, $userPhone, $page, $limit);
        return Format::success($res);
    }
}