<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/15
 * Time: 2:15 AM
 */

namespace admin\modules\v1\controllers;


use common\components\ApiController;
use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\models\Upload;
use Yii;
use yii\web\UploadedFile;

class UploadController extends ApiController
{
    public function actionSave()
    {
        $model = new Upload();
        if (Yii::$app->request->isPost) {
            //$model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $model->imageFiles = UploadedFile::getInstancesByName('imageFiles');
            if ($res = $model->upload()) {
                // 文件上传成功
                return $res;
            }
        }
        throw new DefaultException(ErrorConst::ERROR_UPLOAD_FAIL, json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE) ?? '');
    }
}