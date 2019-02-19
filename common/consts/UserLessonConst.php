<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/18
 * Time: 12:45 AM
 */

namespace common\consts;


class UserLessonConst
{
    const STATUS_INIT = 'init'; //未完成
    const STATUS_FINISH = 'finish'; //完成
    const STATUS_FINISH_DELAY = 'finish_delay'; //非当天完成
    const STATUS_LOCK = 'lock';

    const SHARE_STATUS_INIT = 'init'; //未分享
    const SHARE_STATUS_SUCC = 'succ'; //分享成功
    const SHARE_STATUS_FAIL = 'fail'; //分享失败

    //完成学习
    public static $mapFinish = [
        self::STATUS_FINISH,
        self::STATUS_FINISH_DELAY,
    ];

    /**
     * @var array 课程状态
     */
    public static $statusToText = [
        self::STATUS_LOCK => '待解锁',
        self::STATUS_FINISH => '已完成',
        self::STATUS_FINISH_DELAY => '已完成',
        self::STATUS_INIT => '未完成',
    ];
}