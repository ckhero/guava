<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 5:58 PM
 */

namespace admin\modules\v1\controllers;


use admin\services\DashboardService;
use common\components\Format;

class DashboardController extends AdminController
{
    public function actionIndex()
    {
        $res = (new DashboardService())->index();
        return Format::success($res);
    }
}