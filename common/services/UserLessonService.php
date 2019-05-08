<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/19
 * Time: 11:45 PM
 */

namespace common\services;


use common\consts\LessonConst;
use common\consts\UserLessonConst;
use common\models\Lesson;
use common\models\User;
use common\models\UserLesson;

class UserLessonService
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @var Lesson[] $lessons
     */
    public function list(int $day = null, $limit = 1)
    {
        $list = (new LessonService())->listGroupByDay();
        $data = [];
        $i = 0;
        foreach ($list as $key => $lessons) {
            if ($day && $key < $day) continue;
            if ($key >= $day) $i += 1;
            if ($day && $limit && $limit < $i) break;
            $finishLessonTypeNum = $lessonTypeNum = 0;

            foreach ($lessons as $lesson) {
                $lessonTypeNum += 1;
                /* @var Lesson $lesson */
                $data[$key]['lessons'][$lesson->lessonTypeSort] = [
                    'lesson_id' => $lesson->lesson_id,
                    'lesson_type' => $lesson->lessonTypeText,
                    'lesson_name' => $lesson->lesson_name,
                    'status' => $this->getStatus($lesson->lesson_id),
                ];
                if ($this->isFinish($lesson->lesson_id)) $finishLessonTypeNum += 1;
            }
            $data[$key]['day'] = $key;
            $data[$key]['right_type_num'] = $finishLessonTypeNum;
            $data[$key]['type_num'] = $lessonTypeNum;
        }

        return $data;
    }

    /**
     * @param int $lessonId
     * @return string
     */
    public function getStatus(int $lessonId): string
    {
        $userLesson = (new UserLesson())->getOne($this->user->user_id, $lessonId);
        if ($userLesson && $userLesson->isFinish()) return UserLessonConst::STATUS_FINISH;

        return $this->isUnlock($lessonId) ? UserLessonConst::STATUS_INIT : UserLessonConst::STATUS_LOCK;
    }

    /**
     * @param int $lessonId
     * @return string
     */
    public function getStatusText(int $lessonId): string
    {
        return UserLessonConst::$statusToText[$this->getStatus($lessonId)];
    }

    /**
     * @return bool
     * 课程是否解锁
     */
    public function isUnlock(int $lessonId): bool
    {
        $lesson = (new Lesson())->findByLessonId($lessonId);
        return $this->user->createDays >= $lesson->lesson_sort;
    }

    /**
     * @param User $user
     * @param int $lessonId
     * @return bool
     */
    public function isFinish(int $lessonId)
    {
        $userLesson = (new UserLesson())->getOne($this->user->user_id, $lessonId);
        return $userLesson && $userLesson->isFinish();
    }

    /**
     * @return array
     */
    public function getLearnSchedule()
    {
        $list = (new LessonService())->listGroupByDay();

        $data = [
            LessonConst::TYPE_ENGLISH => [
                'done' => 0,
                'total' => 0,
            ],
            LessonConst::TYPE_MATH => [
                'done' => 0,
                'total' => 0,
            ],
            LessonConst::TYPE_LOGIC => [
                'done' => 0,
                'total' => 0,
            ],
        ];

        foreach ($list as $lessons) {
            foreach ($lessons as $lesson) {
                /* @var Lesson $lesson */
                if ($this->isFinish($lesson->lesson_id)) $data[$lesson->lesson_type]['done'] += 1;
                $data[$lesson->lesson_type]['total'] += 1;
            }
        }
        return $data;
    }

    /**
     * @return float
     */
    public function getLearnRate()
    {
        $schedule = $this->getLearnSchedule();
        $done = $total = 0;

        foreach ($schedule as $item) {
            $done += $item['done'];
            $total += $item['total'];
        }

        return round($done / $total , 2);
    }

    /**
     * @param UserLesson $userLesson
     * @param int $questionId
     * @return null
     */
    public function getUserOption(int $lessonId, int $questionId)
    {
        $userLesson = (new UserLesson())->findByLessonId($this->user->user_id, $lessonId);
        if (!$userLesson) return null;
        $options = json_decode($userLesson->user_lesson_options, true);
        foreach ($options as $option) {
            if ($option['lesson_question_id'] == $questionId) return $option['option'];
        }
        return null;
    }
}