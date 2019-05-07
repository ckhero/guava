<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 1:24 PM
 */

namespace common\consts;


class OrderConst
{
    const STATUS_INIT = 'init';//创建订单
    const STATUS_PAYING = 'paying';//支付中
    const STATUS_SUCCESS = 'success';//支付成功
    const STATUS_FAIL = 'fail';//支付失败

    public static $finalMap = [
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
    ];
}