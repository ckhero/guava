<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 10:46 AM
 */

namespace common\consts;


class ErrorConst
{
    const NO_ERROR = 0;

    const ERROR_SYSTEM_ERROR = 100001;  //系统异常
    const ERROR_SYSTEM_PARAMS = 100002;  //参数错误

    //用户相关
    const ERROR_USER_TOKEN_SAVE_FAIL = 101001;
    const ERROR_USER_SAVE_FAIL = 101002;
    const ERROR_USER_NOT_LOGIN = 101003;
    const ERROR_USER_SIGN_SAVE_FAIL = 101004;
    const ERROR_USER_SIGN_NUM_UPDATE_FAIL = 101005;
    const ERROR_USER_POINT_UPDATE_FAIL = 101006;
    const ERROR_USER_DO_SIGN_FAIL = 101007;
    const ERROR_POINT_LOG_SAVE_FAIL = 101008;

    //课程相关
    const ERROR_LESSON_NOT_EXISTS = 102001;
    const ERROR_LESSON_LOCK = 102002;
    const ERROR_LESSON_ALREADY_DONE = 102003;
    const ERROR_LESSON_NOT_DONE = 102004;

    public static $msg = [
        self::NO_ERROR => '成功',
        self::ERROR_SYSTEM_ERROR => '系统繁忙，请稍后重试',
        self::ERROR_SYSTEM_PARAMS => '参数错误',

        //用户相关
        self::ERROR_USER_TOKEN_SAVE_FAIL => 'token保存失败',
        self::ERROR_USER_SAVE_FAIL => '用户保存失败',
        self::ERROR_USER_NOT_LOGIN => '用户未登陆',
        self::ERROR_USER_SIGN_SAVE_FAIL => '用户签到记录保存失败',
        self::ERROR_USER_SIGN_NUM_UPDATE_FAIL => '用户签到次数更新失败',
        self::ERROR_USER_POINT_UPDATE_FAIL => '用户积分更新失败',
        self::ERROR_USER_DO_SIGN_FAIL => '用户签到失败',

        //积分相关
        self::ERROR_POINT_LOG_SAVE_FAIL => '积分日志添加失败',

        //课程相关
        self::ERROR_LESSON_NOT_EXISTS => '课程不存在',
        self::ERROR_LESSON_LOCK => '课程尚未解锁',
        self::ERROR_LESSON_ALREADY_DONE => '课程已经完成',
        self::ERROR_LESSON_NOT_DONE => '课程尚未完成',
    ];

    /**
     * 根据定义的错误码来显示错误消息，支持sprintf来渲染参数
     *
     * @param int $code
     * @return string
     */
    public static function msg($code)
    {
        $msg = '系统正忙，请稍后重试';
        if (isset(self::$msg[$code])) {
            $msg = self::$msg[$code];
            $num = func_num_args();
            if ($num > 1) {
                $msg = vsprintf($msg, array_slice(func_get_args(), 1));
            }
        }
        return $msg;
    }
}