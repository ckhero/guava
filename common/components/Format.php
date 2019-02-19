<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 12:00 AM
 */

namespace common\components;


use ArrayObject;
use common\consts\ErrorConst;

class Format
{
    /**
     * 正常信息
     * @param null $data  $data请保持object，不要使用数组
     * @param string $message 尽量不要自定义，使用code映射最好
     * @param null $meta 额外信息，如弹窗等
     * @return array
     */
    public static function success($data = NULL, $message = NULL, $meta = NULL)
    {
        $return = [
            'code'      => ErrorConst::NO_ERROR,
            'message'   => isset($message) ? $message : ErrorConst::msg(ErrorConst::NO_ERROR),
            'data'      => isset($data) ? $data : new ArrayObject(),
        ];

        if (!empty($meta)){
            $return['meta'] = $meta;
        }

        return $return;
    }

    /**
     * 失败信息
     * @param int $code
     * @param null $data
     * @param null $message
     * @param null $meta
     * @return array
     */
    public static function fail($code = ErrorConst::ERROR_SYSTEM_ERROR, $data = NULL, $message = NULL, $meta = NULL)
    {
        $return = [
            'code'      => $code,
            'message'   => NULL !== $message ? $message : ErrorConst::msg(ErrorConst::ERROR_SYSTEM_ERROR),
            'data'      => NULL !== $data ? $data : new ArrayObject(),
        ];

        if (!empty($meta)){
            $return['meta'] = $meta;
        }

        return $return;
    }
}