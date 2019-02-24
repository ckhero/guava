<?php

namespace common\models;

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
            'lesson_datum_id' => 'Lesson Datum ID',
            'lesson_datum_lesson_id' => 'Lesson Datum Lesson ID',
            'lesson_datum_datum_id' => 'Lesson Datum Datum ID',
            'lesson_datum_create_at' => 'Lesson Datum Create At',
            'lesson_datum_update_at' => 'Lesson Datum Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatum()
    {
        return $this->hasOne(Datum::className(), ['datum_id' => 'lesson_datum_datum_id']);
    }
}
