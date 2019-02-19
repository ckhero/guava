<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 11:37 PM
 */
namespace common\log_targets;

use yii\helpers\ArrayHelper;
use yii\helpers\BaseJson;
use yii\helpers\VarDumper;
use yii\log\FileTarget;

class ESTarget extends FileTarget
{
    /**
     * format to valid json string
     *
     * @param $message
     * @return string
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;
        $output = [
            'category' => strtolower($category),
            'time' => date('Y-m-d H:i:s', $timestamp),
        ];

        if (is_array($text)) {  //数组类型
            return Basejson::encode(ArrayHelper::merge($output, $text));
        } elseif (is_string($text)) {
            $output['default'] = $text;
            return BaseJson::encode($output);
        } elseif ($text instanceof \Throwable) {
            $text = (string) $text;
        } else {
            $text = VarDumper::export($text);
        }
        $output['default'] = $text;
        return BaseJson::encode($output);
    }
}
