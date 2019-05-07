<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/5/7
 * Time: 9:57 PM
 */

namespace common\helpers;


class StrHelper
{
    public static function genOrderNo()
    {
        $order_id_main = date('YmdHis') . rand(10000000,99999999);

        $order_id_len = strlen($order_id_main);

        $order_id_sum = 0;

        for($i=0; $i<$order_id_len; $i++){

            $order_id_sum += (int)(substr($order_id_main,$i,1));

        }

        $osn = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        return $osn;
    }
}