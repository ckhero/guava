<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/20
 * Time: 2:31 AM
 */

namespace common\services;


use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\models\User;
use common\models\UserLesson;

class ExaminationService
{
    /**
     * @param User $user
     * @param int $lessonId
     * @return array
     * @throws DefaultException
     */
    public function result(User $user, int $lessonId): array
    {
        $userLesson = (new UserLesson())->getOne($user->user_id, $lessonId);
        if (!$userLesson || !$userLesson->isFinish()) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_DONE);

        return [
            'user_lesson_score' => $userLesson->user_lesson_score,
            'user_lesson_right_percent' => $userLesson->user_lesson_right_percent,
        ];
    }
}