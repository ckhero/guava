<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 1:07 AM
 */

namespace common\queries;


use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    /**
     * @param string $openid
     * @return UserQuery
     */
    public function byOpenid(string $openid)
    {
        return $this->andOnCondition(['user_openid' => $openid]);
    }
}