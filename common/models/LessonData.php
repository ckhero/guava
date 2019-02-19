<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lesson_data".
 *
 * @property int $lesson_data_id
 * @property int $lesson_data_lesson_id 课程id
 * @property string $lesson_data_type 课程资料类型[url:链接;]
 * @property string $lesson_data_name 课程资料名字
 * @property string $lesson_data_detail 课程资料内容
 * @property string $lesson_data_create_at 课程资料添加时间
 * @property string $lesson_data_update_at 课程资料更新时间
 */
class LessonData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_data_lesson_id'], 'integer'],
            [['lesson_data_type', 'lesson_data_detail'], 'string'],
            [['lesson_data_detail'], 'required'],
            [['lesson_data_create_at', 'lesson_data_update_at'], 'safe'],
            [['lesson_data_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_data_id' => 'Lesson Data ID',
            'lesson_data_lesson_id' => 'Lesson Data Lesson ID',
            'lesson_data_type' => 'Lesson Data Type',
            'lesson_data_name' => 'Lesson Data Name',
            'lesson_data_detail' => 'Lesson Data Detail',
            'lesson_data_create_at' => 'Lesson Data Create At',
            'lesson_data_update_at' => 'Lesson Data Update At',
        ];
    }
}
