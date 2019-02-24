<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 1:26 PM
 */

namespace common\queries;


use common\consts\OrderConst;
use yii\db\ActiveQuery;

class OrderQuery extends ActiveQuery
{
    /**
     * @return OrderQuery
     */
    public function success()
    {
        return $this->andOnCondition(['order_status' => OrderConst::STATUS_SUCCESS]);
    }

    /**
     * @param int $userId
     * @return OrderQuery
     */
    public function byUserId(int $userId)
    {
        return $this->andOnCondition(['order_user_id' => $userId]);
    }
}