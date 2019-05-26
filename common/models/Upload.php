<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/15
 * Time: 2:17 AM
 */

namespace common\models;


use yii\base\Model;

class Upload extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $files = [];
            foreach ($this->imageFiles as $file) {
                $fileName =   '/' . \Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $path = \Yii::$app->params['uploads']['path'] . date('y') . '/' . date('m') . '/' . date('d');
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $file->saveAs( $path . $fileName);
                $files[] = \Yii::$app->params['uploads']['url'] . $fileName;
            }
            return $files;
        } else {
            return false;
        }
    }
}