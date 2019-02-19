<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/20
 * Time: 1:56 AM
 */

namespace common\services;


use common\models\User;

class HomeService
{
    /**
     * @param User $user
     * @return array
     */
    public function index(User $user): array
    {
        $userLessonService = (new UserLessonService($user));
        return [
            'schedule' => $userLessonService->getLearnSchedule(),
            'today' => $userLessonService->list($user->createDays, 1),
        ];
    }
}