<?php

namespace common\models;

use common\consts\LessonConst;
use Yii;

/**
 * This is the model class for table "lesson".
 *
 * @property int $lesson_id
 * @property string $lesson_type 课程类型[english:英语;math:数学;logic:逻辑]
 * @property string $lesson_name 课程名字
 * @property int $lesson_sort 课程顺序
 * @property string $lesson_create_at 课程创建时间
 * @property string $lesson_update_at 课程更新时间
 *
 * @property string $lessonTypeText 课程类型
 * @property int $lessonTypeSort 课程排序
 * @property LessonData $lessonData 课程学习资料
 * @property LessonQuestion[] $lessonQuestions 课程题目
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_type'], 'string'],
            [['lesson_sort'], 'integer'],
            [['lesson_create_at', 'lesson_update_at'], 'safe'],
            [['lesson_name'], 'string', 'max' => 128],
            [['lesson_type', 'lesson_sort'], 'unique', 'targetAttribute' => ['lesson_type', 'lesson_sort']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_id' => 'Lesson ID',
            'lesson_type' => 'Lesson Type',
            'lesson_name' => 'Lesson Name',
            'lesson_sort' => 'Lesson Sort',
            'lesson_create_at' => 'Lesson Create At',
            'lesson_update_at' => 'Lesson Update At',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]|self[]
     */
    public function list(): array
    {
        return self::find()->all();
    }

    /**
     * 课程类型
     * @return string
     */
    public function getLessonTypeText(): string
    {
        return LessonConst::$typeToText[$this->lesson_type];
    }

    /**
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord|self
     */
    public function findByLessonId(int $lessonId)
    {
        return self::find()->where([
            'lesson_id' => $lessonId
        ])->one();
    }

    /**
     * @return int
     */
    public function getLessonTypeSort():int
    {
        return LessonConst::$typeSort[$this->lesson_type];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonData()
    {
        return $this->hasOne(LessonData::className(), ['lesson_data_lesson_id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonQuestions()
    {
        return $this->hasMany(LessonQuestion::className(), ['lesson_question_lesson_id' => 'lesson_id']);
    }

    /**
     * @return bool
     * 是否需要支付
     */
    public function isNeedPay()
    {
        return $this->lesson_id > 3;
    }
}
