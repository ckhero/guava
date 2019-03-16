<?php

namespace common\models;

use common\queries\OrderQuery;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $order_id
 * @property int $order_user_id 用户id
 * @property string $order_status 订单状态[init:订单生成;paying:支付中;success:支付成功;fail:支付失败]
 * @property string $order_no 订单编号
 * @property string $order_out_trade_no 微信的订单编号
 * @property int $order_amount 订单金额[单位/分]
 * @property string $order_desc 订单描述
 * @property string $order_create_at 订单创建时间
 * @property string $order_update_at 订单更新时间
 *
 * @property string $orderAmount 金额元
 * @property User $user 用户
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_user_id'], 'required'],
            [['order_user_id', 'order_amount'], 'integer'],
            [['order_status'], 'string'],
            [['order_create_at', 'order_update_at'], 'safe'],
            [['order_no', 'order_out_trade_no'], 'string', 'max' => 128],
            [['order_desc'], 'string', 'max' => 256],
        ];
    }

    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_user_id' => 'Order User ID',
            'order_status' => 'Order Status',
            'order_no' => 'Order No',
            'order_out_trade_no' => 'Order Out Trade No',
            'order_amount' => 'Order Amount',
            'order_desc' => 'Order Desc',
            'order_create_at' => 'Order Create At',
            'order_update_at' => 'Order Update At',
        ];
    }

    /**
     * @param int $userId
     * @return array|null|\yii\db\ActiveRecord|self
     */
    public function findFinishOne(int $userId)
    {
        return self::find()->byUserId($userId)->success()->one();
    }

    /**
     * @param $status
     * @param $startTime
     * @param $endTime
     * @param $orderNo
     * @param $page
     * @param $limit
     * @return array
     */
    public function list($status, $startTime, $endTime, $orderNo, $page, $limit)
    {
        $query = self::find();
        $query->filterWhere([
            'order_status' => $status,
            'order_no' => $orderNo,
        ]);
        $query->andFilterWhere(['between', 'order_create_at', $startTime, $endTime]);
        $total = (int) $query->count();
        $query->orderBy('order_id desc');
        $query->offset(($page - 1) * $limit);
        $query->limit($limit);
        $list = $query->all();
        return [$total, $list];
    }

    /**
     * @return int
     */
    public function getOrderAmount()
    {
        return round($this->order_amount / 100, 2);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'order_user_id']);
    }
}
