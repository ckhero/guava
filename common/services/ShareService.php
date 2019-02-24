<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 2:24 PM
 */

namespace common\services;


use common\consts\ShareLogConst;
use common\models\Lesson;
use common\models\ShareLog;
use common\models\User;
use common\models\UserLesson;

class ShareService
{
    /**
     * @param User $user
     * @param int $lessonId
     * @param string $status
     * @return bool
     * @throws \common\exceptions\DefaultException
     */
    public function save(User $user, int $lessonId, string $status = ShareLogConst::STATUS_FAIL)
    {
        $shareLog = (new ShareLog())->create($user->user_id, $lessonId, '答题结束分享', $status);
        if ($shareLog->isSucc()) {
            (new UserLesson())->updateShareStatus($user->user_id, $lessonId);
        }
        return true;
    }
}