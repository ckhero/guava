<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 2:17 PM
 */

namespace admin\services;


use common\models\Order;

class OrderService extends BaseService
{
    public function list($status, $startTime, $endTime, $orderNo, $page, $limit)
    {
        list($total, $orders) = (new Order())->list($status, $startTime, $endTime, $orderNo, $page, $limit);
        /**@var Order $order**/
        $list = [];
        foreach ($orders as $order) {
            $list[] = [
                'order_id' => $order->order_id,
                'order_status' => $order->order_status,
                'order_out_trade_no' => $order->order_out_trade_no,
                'order_no' => $order->order_no,
                'order_amount' => $order->orderAmount,
                'order_create_at' => $order->order_create_at,
                'order_update_at' => $order->order_update_at,
                'user_name' => $order->user->user_name,
                'user_id' => $order->user->user_id,
            ];
        }

        return compact('total', 'list');
    }
}