<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/20
 * Time: 2:31 AM
 */

namespace common\services;


use common\components\Log;
use common\consts\ErrorConst;
use common\consts\UserLessonConst;
use common\exceptions\DefaultException;
use common\models\Lesson;
use common\models\LessonQuestion;
use common\models\PointLog;
use common\models\User;
use common\models\UserLesson;

class ExaminationService
{
    /**
     * @param User $user
     * @param int $lessonId
     * @param array $options
     * @return array
     * @throws DefaultException
     */
    public function save(User $user, int $lessonId, array $options)
    {

         Log::info("考试结果-1", [
             'user_id' => $user->user_id,
             'lesson_id' => $lessonId,
             'options' => $options,
         ]);
        foreach ($options as $k => $item) {
            if (is_null($item)) {
                unset($k);
            }
        }
        Log::info("考试结果-2", [
            'options' => $options,
        ]);
        $lesson = (new Lesson())->findByLessonId($lessonId);
        (new LessonService())->checkPaid($user, $lesson);

        $userLesson = (new UserLesson())->getOne($user->user_id, $lessonId);
        $optionsKeys = array_column($options, 'lesson_question_id');

        Log::info("考试结果-3", [
            'key' => $optionsKeys,
            'questions' => $lesson->lessonQuestions,
        ]);
        if (!$userLesson || !$userLesson->isFinish()) {
            $point = $score = $rightNum = 0;
            $optionsNew = [];

            foreach ($lesson->lessonQuestions as $question) {
                $index = array_search($question->lesson_question_id, $optionsKeys);
                $option = $options[$index];
                $optionsNew[] = $option;
                if ($index !== false) {
                    if ($question->checkOption($option['option'])) {
                        $point +=  $lesson->point;
                        $score += $question->score;
                        $rightNum += 1;
                    }
                }
            }
            $rightPercent = intval($rightNum / $lesson->questionNum * 10000);
            $userLesson = (new UserLesson())->create($user->user_id, $score, $point, $rightPercent, $lessonId, $optionsNew, $rightPercent >= 6000 ? UserLessonConst::STATUS_FINISH :UserLessonConst::STATUS_FAIL);
            $user->updatePoint($point, $userLesson->user_lesson_lesson_id);
        }

        return [
            'user_lesson_score' => $userLesson->user_lesson_score,
            'user_lesson_right_percent' => $userLesson->percent,
            'user_lesson_point' => $userLesson->user_lesson_point,
        ];
    }

    /**
     * @param User $user
     * @param int $lessonId
     * @return array
     * @throws DefaultException
     */
    public function result(User $user, int $lessonId)
    {
        $lesson = (new Lesson())->findByLessonId($lessonId);
        (new LessonService())->checkPaid($user, $lesson);

        $userLesson = (new UserLesson())->getOne($user->user_id, $lessonId);
        if (!$userLesson) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_DONE);
        return [
            'user_lesson_score' => $userLesson->user_lesson_score,
            'user_lesson_right_percent' => $userLesson->percent,
            'user_lesson_point' => $userLesson->user_lesson_point,
        ];
    }
}