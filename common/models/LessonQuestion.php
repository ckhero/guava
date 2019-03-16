<?php

namespace common\models;

use common\consts\ErrorConst;
use common\consts\LessonQuestionItemConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "lesson_question".
 *
 * @property int $lesson_question_id
 * @property int $lesson_question_lesson_id 课程id
 * @property int $lesson_question_sort 题目排序
 * @property string $lesson_question_detail 题目内容
 * @property string $lesson_question_type 题目内容类型[text:文字;img:图片;]
 * @property string $lesson_question_create_at 题目创建时间
 * @property string $lesson_question_update_at 题目更新时间
 *
 * @property LessonQuestionItem[] $lessonQuestionItems 题目更新时间
 * @property LessonQuestionItem $lessonQuestionRightItem 对的选项
 * @property string $rightOption 对的选项
 * @property int $score 题目个数
 */
class LessonQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_question_lesson_id'], 'required'],
            [['lesson_question_lesson_id', 'lesson_question_sort'], 'integer'],
            [['lesson_question_detail', 'lesson_question_type'], 'string'],
            [['lesson_question_create_at', 'lesson_question_update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_question_id' => 'LessonController Question ID',
            'lesson_question_lesson_id' => 'LessonController Question LessonController ID',
            'lesson_question_sort' => 'LessonController Question Sort',
            'lesson_question_detail' => 'LessonController Question Detail',
            'lesson_question_type' => 'LessonController Question Type',
            'lesson_question_create_at' => 'LessonController Question Create At',
            'lesson_question_update_at' => 'LessonController Question Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonQuestionItems()
    {
        return $this->hasMany(LessonQuestionItem::className(), ['lesson_question_lesson_question_id' => 'lesson_question_id'])->orderBy('lesson_question_item_option');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonQuestionRightItem()
    {
        return $this->hasOne(LessonQuestionItem::className(), ['lesson_question_lesson_question_id' => 'lesson_question_id'])
            ->where([
                'lesson_question_item_right' => LessonQuestionItemConst::STATUS_RIGHT_YES
            ]);
    }

    /**
     * @param int $questionId
     * @return LessonQuestion|null
     * @throws DefaultException
     */
    public function findByQuestionId(int $questionId)
    {
        $model = self::findOne($questionId);
        if (!$model) throw new DefaultException(ErrorConst::ERROR_LESSON_QUESTION_ILLEGAL);
        return $model;
    }

    /**
     * @return string
     */
    public function getRightOption(): string
    {
        return $this->lessonQuestionRightItem->lesson_question_item_option;
    }

    /**
     * @param string $option
     * @return bool
     */
    public function checkOption(string $option): bool
    {
        return strcasecmp($this->rightOption, $option) === 0;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return 20;
    }

    /**
     * @param $lessonQuestionId
     * @param $lessonQuestionLessonId
     * @param $lessonQuestionSort
     * @param $lessonQuestionDetail
     * @param $lessonQuestionType
     * @return LessonQuestion|null
     * @throws DefaultException
     */
    public function createOrUpdate($lessonQuestionId, $lessonQuestionLessonId, $lessonQuestionSort, $lessonQuestionDetail, $lessonQuestionType)
    {
        $model = self::findOne($lessonQuestionId);
        if (!$model) $model = new self();
        $model->lesson_question_lesson_id = $lessonQuestionLessonId;
        $model->lesson_question_sort = $lessonQuestionSort;
        $model->lesson_question_detail = $lessonQuestionDetail;
        $model->lesson_question_type = $lessonQuestionType;
        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, json_encode($model->getFirstErrors(), JSON_UNESCAPED_UNICODE));
        return $model;
    }
    public function multiCreateOrUpdate($questions, $lessonId)
    {
        $res = [];
        foreach ($questions as $question) {
            $questionModel = (new self())->createOrUpdate(
                ($question['lesson_question_id'] ?? 0),
                $lessonId,
                ($question['lesson_question_sort'] ?? 1),
                ($question['lesson_question_detail'] ?? ''),
                ($question['lesson_question_type'] ?? 'text')
            );
            $lessonQuestionItem = (new LessonQuestionItem())->mutliCreateOrUpdate($question['lesson_question_items'], $questionModel->lesson_question_id, $question['lesson_question_right_option']);
            $res[] = $questionModel;
        }
        return $res;
    }
}
