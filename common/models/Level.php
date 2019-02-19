<?php

namespace common\models;

use common\consts\RedisConst;
use Yii;

/**
 * This is the model class for table "level".
 *
 * @property int $level_id
 * @property string $level_name 用户等级名称
 * @property int $level_min_point 用户等级最小积分
 * @property int $level_max_point 用户等级最大积分
 * @property string $level_create_at 用户等级创建时间
 * @property string $level_update_at 用户等级更新时间
 */
class Level extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level_min_point', 'level_max_point'], 'integer'],
            [['level_create_at', 'level_update_at'], 'safe'],
            [['level_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'level_id' => 'Level ID',
            'level_name' => 'Level Name',
            'level_min_point' => 'Level Min Point',
            'level_max_point' => 'Level Max Point',
            'level_create_at' => 'Level Create At',
            'level_update_at' => 'Level Update At',
        ];
    }

    /**
     * @return mixed|self[]
     */
    public function list()
    {
        return Yii::$app->cache->getOrSet(__METHOD__ . 'level_list', function () {
            return self::find()->orderBy('level_min_point')->all();
        }, RedisConst::HOUR_ONE);
    }
}
