<?php

namespace common\models;

use common\consts\ErrorConst;
use common\consts\LessonQuestionItemConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "lesson_question_item".
 *
 * @property int $lesson_question_item_id
 * @property int $lesson_question_lesson_question_id 题目ID
 * @property string $lesson_question_item_option 题目选项
 * @property string $lesson_question_item_detail 选项内容
 * @property string $lesson_question_item_right 选项是否正确
 * @property string $lesson_question_item_create_at 选项创建时间
 * @property string $lesson_question_item_update_at 选项更新时间
 */
class LessonQuestionItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_question_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_question_lesson_question_id'], 'required'],
            [['lesson_question_lesson_question_id'], 'integer'],
            [['lesson_question_item_option', 'lesson_question_item_detail', 'lesson_question_item_right'], 'string'],
            [['lesson_question_item_create_at', 'lesson_question_item_update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_question_item_id' => 'LessonController Question Item ID',
            'lesson_question_lesson_question_id' => 'LessonController Question LessonController Question ID',
            'lesson_question_item_option' => 'LessonController Question Item Option',
            'lesson_question_item_detail' => 'LessonController Question Item Detail',
            'lesson_question_item_right' => 'LessonController Question Item Right',
            'lesson_question_item_create_at' => 'LessonController Question Item Create At',
            'lesson_question_item_update_at' => 'LessonController Question Item Update At',
        ];
    }

    /**
     * @param $id
     * @param $questionId
     * @param $option
     * @param $detail
     * @param $right
     * @return LessonQuestionItem|null
     * @throws DefaultException
     */
    public function createOrUpdate($id, $questionId, $option, $detail, $right)
    {
        $model = self::findOne($id);
        if (!$model) $model = new self();
        $model->lesson_question_lesson_question_id = $questionId;
        $model->lesson_question_item_option = $option;
        $model->lesson_question_item_detail = $detail;
        $model->lesson_question_item_right = $right;
        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, json_encode($model->getFirstErrors(), JSON_UNESCAPED_UNICODE));
        return $model;
    }

    /**
     * @param $options
     * @param $questionId
     * @param $rightOption
     * @return array
     * @throws DefaultException
     */
    public function mutliCreateOrUpdate($options, $questionId, $rightOption)
    {
        $res = [];
        foreach ($options as $option) {
            $res[] = (new self())->createOrUpdate(
                $option['lesson_question_item_id'] ?? 0,
                $questionId, $option['lesson_question_item_option'],
                $option['lesson_question_item_detail'],
                $option['lesson_question_item_option'] === $rightOption ? LessonQuestionItemConst::STATUS_RIGHT_YES : LessonQuestionItemConst::STATUS_RIGHT_NO
                );
        }
        return $res;
    }
}
