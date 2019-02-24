<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/20
 * Time: 2:31 AM
 */

namespace common\services;


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
        $lesson = (new Lesson())->findByLessonId($lessonId);
        (new LessonService())->checkPaid($user, $lesson);

        $userLesson = (new UserLesson())->getOne($user->user_id, $lessonId);

        if (!$userLesson || !$userLesson->isFinish()) {
            $point = $score = $rightNum = 0;

            foreach ($options as $option) {

                $lessonQuestion = (new LessonQuestion())->findByQuestionId($option['lesson_question_id']);
                if ($lessonQuestion->checkOption($option['option'])) {
                    $point +=  $lesson->point;
                    $score += $lessonQuestion->score;
                    $rightNum += 1;
                }
            }
            $rightPercent = intval($rightNum / $lesson->questionNum * 10000);
            $userLesson = (new UserLesson())->create($user->user_id, $score, $rightPercent, $lessonId, $options, $rightPercent >= 60 ? UserLessonConst::STATUS_FINISH :UserLessonConst::STATUS_FAIL);
            $user->updatePoint($point, $userLesson->user_lesson_lesson_id);
        }

        return [
            'user_lesson_score' => $userLesson->user_lesson_score,
            'user_lesson_right_percent' => $userLesson->percent,
            'point' => $userLesson->user_lesson_point,
        ];
    }
}