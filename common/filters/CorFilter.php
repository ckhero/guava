<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 11:25 PM
 */

namespace common\filters;


use Yii;
use yii\filters\Cors;

class CorFilter extends Cors
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->getCors();
        $result = parent::beforeAction($action);
        //跨域请求，直接返回
        if (Yii::$app->request->method == 'OPTIONS') {
            return true;
        }
        return $result;
    }

    /**
     * @method 获取Cors配置；默认使用父类自带的配置；
     */
    public function getCors()
    {
        if (array_key_exists('cors', Yii::$app->params)) {
            $this->cors = Yii::$app->params['cors'];
        }
    }

    /**
     * @method 处理泛域名跨域
     *
     * @param string $requestHeaderOrigin
     * @param array $CorOrigin
     * @return bool 默认返回false，不影响流程；
     */
    public function beforePrepareHeaderForOrigin($requestHeaderOrigin = '', $CorOrigin = [])
    {
        if (!is_array($CorOrigin) || !is_string($requestHeaderOrigin)) {
            return false;
        }
        foreach ($CorOrigin as $value) {
            if (!preg_match('/\*/', $value)) {
                continue;
            }
            $value = preg_replace(['/\:/', '/\//', '/\./', '/\-/', '/\*/'], ['\:', '\/', '\.', '\-', '(.*?)'], $value);
            if (preg_match('/^' . $value . '$/', $requestHeaderOrigin, $a)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @method 覆盖父类方法，处理泛域名跨域
     *
     * For each CORS headers create the specific response
     * @param array $requestHeaders CORS headers we have detected
     * @return array CORS headers ready to be sent
     */
    public function prepareHeaders($requestHeaders)
    {
        $responseHeaders = [];
        // handle Origin
        if (isset($requestHeaders['Origin'], $this->cors['Origin'])) {

            //处理泛域名跨域，默认返回false add by gordon 2017-7-25
            $isMatch = $this->beforePrepareHeaderForOrigin($requestHeaders['Origin'], $this->cors['Origin']);

            if (in_array('*', $this->cors['Origin']) || in_array($requestHeaders['Origin'],
                    $this->cors['Origin']) || $isMatch
            ) {
                $responseHeaders['Access-Control-Allow-Origin'] = $requestHeaders['Origin'];
            }
        }

        $this->prepareAllowHeaders('Headers', $requestHeaders, $responseHeaders);

        if (isset($requestHeaders['Access-Control-Request-Method'])) {
            $responseHeaders['Access-Control-Allow-Methods'] = implode(', ',
                $this->cors['Access-Control-Request-Method']);
        }

        if (isset($this->cors['Access-Control-Allow-Credentials'])) {
            $responseHeaders['Access-Control-Allow-Credentials'] = $this->cors['Access-Control-Allow-Credentials'] ? 'true' : 'false';
        }

        if (isset($this->cors['Access-Control-Max-Age']) && Yii::$app->getRequest()->getIsOptions()) {
            $responseHeaders['Access-Control-Max-Age'] = $this->cors['Access-Control-Max-Age'];
        }

        if (isset($this->cors['Access-Control-Expose-Headers'])) {
            $responseHeaders['Access-Control-Expose-Headers'] = implode(', ',
                $this->cors['Access-Control-Expose-Headers']);
        }

        return $responseHeaders;
    }
}