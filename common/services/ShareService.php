<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 2:24 PM
 */

namespace common\services;


use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
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
    public function save(User $user, int $lessonId, string $status)
    {
        try {
            $shareLog = (new ShareLog())->create($user->user_id, $lessonId, '答题结束分享', $status);
            if ($shareLog->isSucc()) {
                (new UserLesson())->updateShareStatus($user->user_id, $lessonId);
            }
        } catch (\Exception $e) {
            Log::warning(ErrorConst::msg(ErrorConst::ERROR_SHARE_SAVE_FAIL), [
                'user_id' => $user->user_id,
                'lesson_id' => $lessonId,
                'status' => $status,
                'message' => $e->getMessage()
            ], LogTypeConst::TYPE_SAHRE);
        }
        return true;
    }
}