<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/19
 * Time: 11:15 PM
 */

namespace common\services;


use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\models\Lesson;
use common\models\User;

class LessonService
{
    /**
     * @return array|[self[]]
     */
    public function listGroupByDay()
    {
        $list = (new Lesson())->list();
        $lessons = [];
        foreach ($list as $lesson) {
            $lessons[$lesson->lesson_sort][] = $lesson;
        }
        ksort($lessons);
        //var_dump($lessons);;exit;
        return $lessons;
    }

    /**
     * @param User $user
     * @param int $lessonId
     * @return array
     * @throws DefaultException
     */
    public function detail(User $user, int $lessonId):array
    {
        $lesson = (new Lesson())->findByLessonId($lessonId);
        if (!$lesson) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_EXISTS);

        if (!(new UserLessonService($user))->isUnlock($lessonId)) throw new DefaultException(ErrorConst::ERROR_LESSON_LOCK);

        if ((new UserLessonService($user))->isFinish($lessonId)) throw new DefaultException(ErrorConst::ERROR_LESSON_ALREADY_DONE);

        $lessonData = [
            'lesson_data_type' => $lesson->lessonData->lesson_data_type,
            'lesson_data_name' => $lesson->lessonData->lesson_data_name,
            'lesson_data_detail' => $lesson->lessonData->lesson_data_detail,
        ];

        $questions = [];
        foreach ($lesson->lessonQuestions as $lessonQuestion) {
            $options = [];
            foreach ($lessonQuestion->lessonQuestionItems as $item) {
                $options[$item->lesson_question_item_option] = [
                    'lesson_question_item_option' => $item->lesson_question_item_option,
                    'lesson_question_item_detail' => $item->lesson_question_item_detail,
                ];
            }
            ksort($options);
            $questions[$lessonQuestion->lesson_question_sort] = [
                'lesson_question_id' => $lessonQuestion->lesson_question_id,
                'lesson_question_sort' => $lessonQuestion->lesson_question_sort,
                'lesson_question_type' => $lessonQuestion->lesson_question_type,
                'lesson_question_detail' => $lessonQuestion->lesson_question_detail,
                'options' => $options,
            ];
        }
        return [
            'lesson_data' => $lessonData,
            'questions' => $questions,
        ];
    }
}