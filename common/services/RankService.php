<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 11:07 AM
 */

namespace common\services;


use common\models\User;

class RankService
{
    /**
     * @return array
     */
    public function list()
    {
        $userList = (new User())->getRankList();
        $rankList = [];

        foreach ($userList as $user) {
            $rankList[] = [
                'name' => $user->user_name,
                'head_img' => $user->user_head_img,
                'point' => $user->user_point,
                'level_name' => $user->levelName,
            ];
        }
        return $rankList;
    }
}