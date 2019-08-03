<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pay_log".
 *
 * @property int $pay_log_id
 * @property int $pay_log_order_id 订单id
 * @property int $pay_log_user_id 订单id
 * @property string $pay_log_content 微信支付结果通知内容
 * @property string $pay_log_create_at 支付日志创建时间
 */
class PayLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_log_order_id'], 'required'],
            [['pay_log_order_id','pay_log_user_id'], 'integer'],
            [['pay_log_content'], 'string'],
            [['pay_log_create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pay_log_id' => 'Pay Log ID',
            'pay_log_order_id' => 'Pay Log Order ID',
            'pay_log_user_id' => 'Pay Log Order ID',
            'pay_log_content' => 'Pay Log Content',
            'pay_log_create_at' => 'Pay Log Create At',
        ];
    }

    public static function addOne($userId, $orderId, $payLogContent) {
        $model = new self();
        $model->pay_log_user_id = $userId;
        $model->pay_log_order_id = $orderId;
        $model->pay_log_content = $payLogContent;
        $model->save();
    }
}
