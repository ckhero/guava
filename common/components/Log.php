<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 11:28 PM
 */

namespace common\components;


use Yii;

class Log
{
    /**
     * @param        $subject  - 某类log的简单描述，力求简洁明了
     * @param        $body     - log的详细内容，支持string，array, 允许为空
     * @param string $category - 日志类别，同Yii::info中的$category
     */
    public static function info($subject, $body = '', $category = 'application')
    {
        $message = self::formatMessage($subject, $body, $category);
        Yii::info($message, $category);
    }

    /**
     * @param        $subject  - 某类log的简单描述，力求简洁明了
     * @param        $body     - log的详细内容，支持string，array
     * @param string $category - 日志类别，同Yii::warning$category
     */
    public static function warning($subject, $body = '', $category = 'application')
    {
        $message = self::formatMessage($subject, $body, $category);
        Yii::warning($message, $category);
    }

    /**
     * @param        $subject  - 某类log的简单描述，力求简洁明了
     * @param        $body     - log的详细内容，支持string，array
     * @param string $category - 日志类别，同Yii::error$category
     */
    public static function error($subject, $body = '', $category = 'application')
    {
        $message = self::formatMessage($subject, $body, $category);
        Yii::error($message, $category);
    }

    /**
     * 格式化成数组
     * @param $subject
     * @param string $body
     * @return array|string
     * @throws ErrorException
     */
    private static function formatMessage($subject, $body = '', $category)
    {
        if (!isset($subject) || !isset($body)) {
            Yii::error('forbid to log null, category - ' . $category);
        }
        // 临时注释,务删
//        elseif (!isset(LogTypeConst::$map[$category])) {
//            Yii::warning('请在LogTypeConst增加日志类型' . $category);
//        }

        if (is_string($body)) {  //string
            $message = [
                'overview' => $subject,
                'detail' => $body
            ];
            return $message;
        } elseif (!is_array($body)) {   // neither array nor string
            $message['overview'] = $subject;
            $message['detail'] = var_export($body, true);
            return $message;
        }

        //array
        $body['overview'] = $subject;
        return $body;   //must be array
    }

    /**
     * 开发测试使用
     *
     * @param \Exception $e
     * @param string     $sub
     */
    public static function show(\Exception $e, String $sub = null)
    {
        if (YII_ENV_BETA or YII_ENV_PROD) {
            return;
        }

        echo '----------------------------------' . PHP_EOL;
        echo sprintf("Subject：%s\nFile：%s\nLine：%s\nCode：%s\nMessage：%s\n", $sub, $e->getFile(), $e->getLine(),
            $e->getCode(), $e->getMessage());
        echo '----------------------------------' . PHP_EOL;
        die;
    }

    /**
     * debug调试用
     */
    public static function debug()
    {
        $file = '/tmp/paydayloan_' . date('Ymd') . '.log';

        $params = func_get_args();
        foreach ($params as $param) {
            if (is_array($param) || is_object($param)) {
                $param = print_r($param, 1);
            } elseif (is_resource($param)) {
                $msgType = get_resource_type($param);
                $param = "resource of type ($msgType)";
            }
            $message = sprintf("%s\t%s\t\n", "[" . date("Y-m-d H:i:s") . "]", $param);
            file_put_contents($file, $message, FILE_APPEND);
        }
    }
    /**
     * 线上log
     *
     * @param \Exception $e
     * @param string     $sub
     * @param string     $param
     *
     * @return string
     */
    public static function sprintf(\Exception $e, String $sub = 'Error', String $param = ''): String
    {
        return sprintf("Subject：%s\nFile：%s\nLine：%s\nCode：%s\nMessage：%s\n参数：%s\n", $sub, $e->getFile(),
            $e->getLine(), $e->getCode(), $e->getMessage(), $param);
    }

    /**
     * 日志记录，es单索引
     * @param $overview
     * @param $params
     * @param $classify
     * @param string $reclassify
     */
    public static function monitor($overview, $params, $classify, $reclassify = "")
    {
        $params = is_array($params) ? ["params" => json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)] : ["params" => $params];
        $data = array_merge($params, ["reclassify" => $reclassify, "classify" => $classify]);
        self::info($overview, $data, "monitor");
    }
}
