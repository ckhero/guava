<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/16
 * Time: 10:51 AM
 */

namespace common\exceptions;


use Yii;
use yii\base\UserException;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\Response;

class ApiErrorHandler extends ErrorHandler
{
    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new Response();
        }

        $ourError = $exception instanceof UserException;

        if ($ourError && $this->errorAction !== null) {
            $result = Yii::$app->runAction($this->errorAction);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response->data = $result;
            }
        } elseif ($response->format === Response::FORMAT_RAW) {
            $response->data = static::convertExceptionToString($exception);
        } else {
            $response->data = $this->convertExceptionToArray($exception);
        }

        //HttpException继承了UserException,需要单独处理
        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->statusCode);
        } elseif ($ourError) {
            $response->data = [
                'code' => $response->data['code'],
                'message' => $response->data['message'],
            ];
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(500);
        }

        $response->send();
    }
}