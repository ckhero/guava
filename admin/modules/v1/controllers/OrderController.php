<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 2:15 PM
 */

namespace admin\modules\v1\controllers;


use admin\services\OrderService;
use common\components\Format;

class OrderController extends AdminController
{
    public function actionList()
    {
        $status = $this->getParam('status');
        $startTime = $this->getParam('start_time');
        $endTime = $this->getParam('end_time');
        $orderNo = $this->getParam('order_no');
        $page = $this->getParam('page', 1);
        $limit = $this->getParam('limit', 20);

        $res = (new OrderService())->list($status, $startTime, $endTime, $orderNo, $page, $limit);
        return Format::success($res);
    }
}