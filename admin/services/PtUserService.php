<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/16
 * Time: 3:56 PM
 */

namespace admin\services;


use common\models\User;

class PtUserService extends BaseService
{
    /**
     * @param $userPayStatus
     * @param $userId
     * @param $userName
     * @param $userPhone
     * @param $page
     * @param $limit
     * @return array
     */
    public function list($userPayStatus, $userId, $userName, $userPhone, $page, $limit)
    {
        list($total, $users) = (new User())->list($userPayStatus, $userId, $userName, $userPhone, $page, $limit);
        $list = [];
        /**@var \common\models\User $user**/
        foreach ($users as $user) {
            $list[] = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_head_img' => $user->user_head_img,
                'user_phone' => $user->user_phone,
                'user_pay_status' => $user->user_pay_status,
                'user_sign_num' => $user->user_sign_num,
                'user_point' => $user->user_point,
                'user_create_at' => $user->user_create_at,
            ];
        }

        return compact('total', 'list');
    }
}