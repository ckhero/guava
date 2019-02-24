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
use common\models\Order;
use common\models\User;
use phpDocumentor\Reflection\Types\Array_;

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
    public function detail(User $user, int $lessonId): array
    {
        $lesson = (new Lesson())->findByLessonId($lessonId);

        $this->checkPaid($user, $lesson);

        if (!$lesson) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_EXISTS);

        if (!(new UserLessonService($user))->isUnlock($lessonId)) throw new DefaultException(ErrorConst::ERROR_LESSON_LOCK);

        if ((new UserLessonService($user))->isFinish($lessonId)) throw new DefaultException(ErrorConst::ERROR_LESSON_ALREADY_DONE);

        $lessonData = [
            'datum_type' => $lesson->lessonDdtum->datum->datum_detail_type,
            'datum_name' => $lesson->lessonDdtum->datum->datum_name,
            'datum_detail' => $lesson->lessonDdtum->datum->datum_detail,
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

    /**
     * @param User $user
     * @param int $lessonId
     * @return array
     */
    public function payStatus(User $user, int $lessonId): array
    {
        $lesson = (new Lesson())->findByLessonId($lessonId);
        return ['status' => $this->isPaid($user, $lesson)];
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @return bool
     */
    public function isPaid(User $user, Lesson $lesson):bool
    {
        if (!$lesson->isNeedPay()) return true;
        return (bool) (new Order())->findFinishOne($user->user_id);
    }

    /**
     * @param User $user
     * @param Lesson $lesson
     * @throws DefaultException
     */
    public function checkPaid(User $user, Lesson $lesson)
    {
        if (!$this->isPaid($user, $lesson)) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_PAID);
    }
}