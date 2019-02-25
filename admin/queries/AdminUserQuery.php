<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 12:40 AM
 */

namespace admin\queries;


use common\consts\AdminUserConst;
use yii\db\ActiveQuery;

class AdminUserQuery extends ActiveQuery
{
    public function byToken($token)
    {
        return $this->andOnCondition(['admin_user_token' => $token]);
    }

    public function byEmail($email)
    {
        return $this->andOnCondition(['admin_user_email' => $email]);
    }

    public function byPassword($password)
    {
        return $this->andOnCondition(['admin_user_password' => $password]);
    }

    public function valid()
    {
        return $this->andOnCondition(['admin_user_status' => AdminUserConst::STATUS_VALID]);
    }

    public function invalid()
    {
        return $this->andOnCondition(['admin_user_status' => AdminUserConst::STATUS_INVALID]);
    }
}