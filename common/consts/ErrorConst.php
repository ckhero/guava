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
    const ERROR_UPLOAD_FAIL = 100003;

    //用户相关
    const ERROR_USER_TOKEN_SAVE_FAIL = 101001;
    const ERROR_USER_SAVE_FAIL = 101002;
    const ERROR_USER_NOT_LOGIN = 101003;
    const ERROR_USER_SIGN_SAVE_FAIL = 101004;
    const ERROR_USER_SIGN_NUM_UPDATE_FAIL = 101005;
    const ERROR_USER_POINT_UPDATE_FAIL = 101006;
    const ERROR_USER_DO_SIGN_FAIL = 101007;
    const ERROR_POINT_LOG_SAVE_FAIL = 101008;
    const ERROR_LOGIN_FAIL = 101009;

    //课程相关
    const ERROR_LESSON_NOT_EXISTS = 102001;
    const ERROR_LESSON_LOCK = 102002;
    const ERROR_LESSON_ALREADY_DONE = 102003;
    const ERROR_LESSON_NOT_DONE = 102004;
    const ERROR_LESSON_NOT_PAID = 102005;
    const ERROR_USER_LESSON_SHARE_STATUS_UPDATE_FAIL = 102006;
    const ERROR_LESSON_QUESTION_ILLEGAL= 102007;
    const ERROR_LESSON_UNKOWN_TYPE= 102008;
    const ERROR_USER_LESSON_SAVE_FAIL= 102009;

    //分享
    const ERROR_SHARE_SAVE_FAIL = 103005;

    //后台相关
    const ERROR_ADMIN_USER_SAVE_FAIL = 104006;
    const ERROR_ADMIN_USER_LOGIN_FAIL = 104007;
    const ERROR_ADMIN_USER_PASSWORD_INVALID = 104008;
    const ERROR_ADMIN_USER_NOT_LOGIN = 104009;
    const ERROR_ADMIN_USER_NOT_EXISTS = 104010;
    const ERROR_ADMIN_USER_EMAIL_USED = 104011;
    const ERROR_ADMIN_USER_ROLE_SAVE_FAIL = 104012;
    const ERROR_ADMIN_ROLE_SAVE_FAIL = 104013;
    const ERROR_ADMIN_ROLE_PRIVILGE_SAVE_FAIL = 104014;
    const ERROR_ADMIN_PRIVILGE_SAVE_FAIL = 104015;
    const ERROR_ADMIN_USER_NAME_USED = 104016;

    //支付
    const ERROR_ORDER_PAYING = 105001;
    const ERROR_ORDER_DONE = 105002;

    public static $msg = [
        self::NO_ERROR => '成功',
        self::ERROR_SYSTEM_ERROR => '系统繁忙，请稍后重试',
        self::ERROR_SYSTEM_PARAMS => '参数错误',
        self::ERROR_UPLOAD_FAIL => '上传失败',

        //用户相关
        self::ERROR_USER_TOKEN_SAVE_FAIL => 'token保存失败',
        self::ERROR_USER_SAVE_FAIL => '用户保存失败',
        self::ERROR_USER_NOT_LOGIN => '用户未登陆',
        self::ERROR_USER_SIGN_SAVE_FAIL => '用户签到记录保存失败',
        self::ERROR_USER_SIGN_NUM_UPDATE_FAIL => '用户签到次数更新失败',
        self::ERROR_USER_POINT_UPDATE_FAIL => '用户积分更新失败',
        self::ERROR_USER_DO_SIGN_FAIL => '用户签到失败',
        self::ERROR_LOGIN_FAIL => '用户登录失败',

        //积分相关
        self::ERROR_POINT_LOG_SAVE_FAIL => '积分日志添加失败',

        //课程相关
        self::ERROR_LESSON_NOT_EXISTS => '课程不存在',
        self::ERROR_LESSON_LOCK => '课程尚未解锁',
        self::ERROR_LESSON_ALREADY_DONE => '课程已经完成',
        self::ERROR_LESSON_NOT_DONE => '课程尚未完成',
        self::ERROR_LESSON_NOT_PAID => '尚未购买课程',
        self::ERROR_USER_LESSON_SHARE_STATUS_UPDATE_FAIL => '用户课程分享状态更新失败',
        self::ERROR_LESSON_QUESTION_ILLEGAL => '无效的题目',
        self::ERROR_LESSON_UNKOWN_TYPE => '未知的课程类型',
        self::ERROR_USER_LESSON_SAVE_FAIL => '用户学习记录保存失败',

        //分享
        self::ERROR_SHARE_SAVE_FAIL => '分享保存失败',

        //后台相关
        self::ERROR_ADMIN_USER_SAVE_FAIL => '用户保存失败',
        self::ERROR_ADMIN_USER_LOGIN_FAIL => '用户登录失败',
        self::ERROR_ADMIN_USER_PASSWORD_INVALID => '密码不正确',
        self::ERROR_ADMIN_USER_NOT_LOGIN => '用户尚未登陆',
        self::ERROR_ADMIN_USER_NOT_EXISTS => '用户不存在',
        self::ERROR_ADMIN_USER_EMAIL_USED => '邮箱已被使用',
        self::ERROR_ADMIN_USER_ROLE_SAVE_FAIL => '用户角色更新失败',
        self::ERROR_ADMIN_ROLE_SAVE_FAIL => '角色更新失败',
        self::ERROR_ADMIN_ROLE_PRIVILGE_SAVE_FAIL => '角色权限更新失败',
        self::ERROR_ADMIN_PRIVILGE_SAVE_FAIL => '权限更新失败',
        self::ERROR_ADMIN_USER_NAME_USED => '用户名已被使用',


        self::ERROR_ORDER_PAYING => '有正在处理中的订单',
        self::ERROR_ORDER_DONE => '支付已完成，请重新进入小程序',
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