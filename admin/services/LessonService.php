<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/13
 * Time: 12:31 AM
 */

namespace admin\services;


use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\models\Datum;
use common\models\Lesson;
use common\models\LessonDatum;
use common\models\LessonQuestion;

class LessonService extends BaseService
{
    /**
     * @param $lessonType
     * @param $lessonName
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function getList($lessonType, $lessonName, $currPage, $pageSize)
    {
        list($total, $lessons) = (new Lesson())->getListByCondition($lessonType, $lessonName, $currPage, $pageSize);
        $list = [];
        /**@var Lesson $lesson**/
        foreach ($lessons as $lesson) {
            $questions = [];
            foreach ($lesson->lessonQuestions as $lessonQuestion) {
                $lessonQuestionItems = [];
                foreach ($lessonQuestion->lessonQuestionItems as $lessonQuestionItem) {
                    $lessonQuestionItems[] = [
                        'lesson_question_item_id' => $lessonQuestionItem->lesson_question_item_id,
                        'lesson_question_item_option' => $lessonQuestionItem->lesson_question_item_option,
                        'lesson_question_item_detail' => $lessonQuestionItem->lesson_question_item_detail,
                        'lesson_question_item_right' => $lessonQuestionItem->lesson_question_item_right,
                    ];
                }
                $questions[] = [
                    'lesson_question_items' => $lessonQuestionItems,
                    'lesson_question_id' => $lessonQuestion->lesson_question_id,
                    'lesson_question_detail' => $lessonQuestion->lesson_question_detail,
                    'lesson_question_type' => $lessonQuestion->lesson_question_type,
                    'lesson_question_sort' => $lessonQuestion->lesson_question_sort,
                    'lesson_question_right_option' => $lessonQuestion->lessonQuestionRightItem->lesson_question_item_option ?? '',
                ];
            }
            $list[] = [
                'lesson_id' => $lesson->lesson_id,
                'lesson_type' => $lesson->lesson_type,
                'lesson_name' => $lesson->lesson_name,
                'lesson_sort' => $lesson->lesson_sort,
                'datum' => [
                    'datum_id' => $lesson->lessonDatum->datum->datum_id ?? '',
                    'datum_name' => $lesson->lessonDatum->datum->datum_name ?? '',
                    'datum_detail' => $lesson->lessonDatum->datum->datum_detail ?? '',
                    'datum_type' => $lesson->lessonDatum->datum->datum_type ?? '',
                    'datum_detail_type' => $lesson->lessonDatum->datum->datum_detail_type ?? '',
                ],
                'questions' => $questions
            ];
        }
        return compact('total', 'list');
    }

    /**
     * @param $lessonId
     * @param $lessonType
     * @param $lessonName
     * @param $lessonSort
     * @return array|Lesson|null|\yii\db\ActiveRecord
     * @throws \common\exceptions\DefaultException
     */
    public function create($lessonId, $lessonType, $lessonName, $lessonSort, $datum, $questions)
    {
        if ($lessonSort <= 0 || !$lessonName || !$datum['datum_name'] | !$datum['datum_detail']) throw new DefaultException(ErrorConst::ERROR_SYSTEM_PARAMS);
        $tran = \Yii::$app->db->beginTransaction();
        try {
            $lesson = (new Lesson())->createOrUpdate($lessonId, $lessonType, $lessonName, $lessonSort);
            $datum = (new Datum())->createOrUpdate($datum['datum_id'], $datum['datum_name'], $datum['datum_detail']);
            (new LessonDatum())->createOrUpdate($lesson->lesson_id, $datum->datum_id);
            (new LessonQuestion())->multiCreateOrUpdate($questions, $lesson->lesson_id);
            $tran->commit();
        } catch (\Exception $e) {
            $tran->rollBack();
            throw $e;
        }
        return $lesson;
    }
}