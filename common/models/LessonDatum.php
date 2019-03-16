<?php

namespace common\models;

use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "lesson_datum".
 *
 * @property int $lesson_datum_id
 * @property int $lesson_datum_lesson_id 课程id
 * @property int $lesson_datum_datum_id 资料id
 * @property string $lesson_datum_create_at 课程资料添加时间
 * @property string $lesson_datum_update_at 课程资料更新时间
 *
 * @property Datum $datum 课程资料更新时间
 */
class LessonDatum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_datum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_datum_lesson_id', 'lesson_datum_datum_id'], 'integer'],
            [['lesson_datum_create_at', 'lesson_datum_update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_datum_id' => 'LessonController Datum ID',
            'lesson_datum_lesson_id' => 'LessonController Datum LessonController ID',
            'lesson_datum_datum_id' => 'LessonController Datum Datum ID',
            'lesson_datum_create_at' => 'LessonController Datum Create At',
            'lesson_datum_update_at' => 'LessonController Datum Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatum()
    {
        return $this->hasOne(Datum::className(), ['datum_id' => 'lesson_datum_datum_id']);
    }

    /**
     * @param $lessonId
     * @param $datumId
     * @return array|LessonDatum|null|\yii\db\ActiveRecord
     * @throws DefaultException
     */
    public function createOrUpdate($lessonId, $datumId)
    {
        $model = (new self())->findByLessonId($lessonId);
        if (!$model) $model = new self();
        $model->lesson_datum_lesson_id = $lessonId;
        $model->lesson_datum_datum_id = $datumId;

        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, json_encode($model->getFirstErrors(), JSON_UNESCAPED_UNICODE));
        return $model;
    }

    /**
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByLessonId(int $lessonId)
    {
        return self::find()->where([
            'lesson_datum_lesson_id' => $lessonId
        ])->one();
    }
}
