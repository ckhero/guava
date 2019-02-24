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
}
