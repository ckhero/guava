<?php

namespace common\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\consts\PointLogConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "point_log".
 *
 * @property int $point_log_id
 * @property int $point_log_user_id 用户id
 * @property int $point_log_point 积分值
 * @property string $point_log_type 积分类型question:答题;
 * @property string $point_log_action_type 积分操作类型sub:减少;add:增加
 * @property string $point_log_desc 日志描述
 * @property string $point_log_create_at 日志创建时间
 * @property string $point_log_update_at 日志更新时间
 */
class PointLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'point_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['point_log_user_id', 'point_log_point'], 'integer'],
            [['point_log_type', 'point_log_action_type'], 'string'],
            [['point_log_create_at', 'point_log_update_at'], 'safe'],
            [['point_log_desc'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'point_log_id' => 'Point Log ID',
            'point_log_user_id' => 'Point Log User ID',
            'point_log_point' => 'Point Log Point',
            'point_log_type' => 'Point Log Type',
            'point_log_action_type' => 'Point Log Action Type',
            'point_log_desc' => 'Point Log Desc',
            'point_log_create_at' => 'Point Log Create At',
            'point_log_update_at' => 'Point Log Update At',
        ];
    }

    /**
     * @param int $userId
     * @param int $point
     * @param string $desc
     * @param string $type
     * @param string $actionType
     * @return PointLog
     * @throws DefaultException
     */
    public function create(int $userId, int $point, string $desc = '', string $type = PointLogConst::TYPE_QUESTION, $actionType = PointLogConst::ACTION_TYPE_ADD): self
    {
        $model = new self();
        $model->point_log_user_id = $userId;
        $model->point_log_point = $point;
        $model->point_log_desc = $desc;
        $model->point_log_type = $type;
        $model->point_log_action_type = $actionType;

        if (!$model->save()) {
            Log::warning(ErrorConst::msg(ErrorConst::ERROR_POINT_LOG_SAVE_FAIL), [
                'message' => $model->getFirstErrors(),
                'user_id' => $userId
            ], LogTypeConst::TYPE_POINT);
            throw new DefaultException(ErrorConst::ERROR_POINT_LOG_SAVE_FAIL);
        }

        return $model;
    }
}
