<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 12:42 AM
 */

namespace common\queries;


use Carbon\Carbon;
use yii\db\ActiveQuery;

class UserTokenQuery extends ActiveQuery
{
    /**
     * @return UserTokenQuery
     */
    public function isActive()
    {
        return $this->andFilterWhere(['>', 'user_token_expire_at',  Carbon::now()]);
    }

    /**
     * @param int $userId
     * @return UserTokenQuery
     */
    public function byUserId(int $userId)
    {
        return $this->andFilterWhere(['user_token_user_id' => $userId]);
    }
}