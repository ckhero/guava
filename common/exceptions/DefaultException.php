<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 10:44 AM
 */

namespace common\exceptions;

use common\consts\ErrorConst;
use Exception;
use yii\base\UserException;

class DefaultException extends UserException
{
    public function __construct($code, $message = null, Exception $previous = null)
    {
        $message = isset($message) ? $message : ErrorConst::msg($code);
        parent::__construct($message, $code, $previous);
    }
}