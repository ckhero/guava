<?php

namespace common\models;

use common\consts\ErrorConst;
use common\consts\ShareLogConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "share_log".
 *
 * @property int $share_log_id
 * @property int $share_log_user_id 用户id
 * @property int $share_log_lesson_id 课程id
 * @property string $share_log_desc 分享描述
 * @property string $share_log_status 分享状态
 * @property string $share_log_create_at 分享时间
 * @property string $share_log_update_at 分享更新时间
 */
class ShareLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'share_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['share_log_user_id', 'share_log_lesson_id'], 'integer'],
            [['share_log_status'], 'string'],
            [['share_log_create_at', 'share_log_update_at'], 'safe'],
            [['share_log_desc'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'share_log_id' => 'Share Log ID',
            'share_log_user_id' => 'Share Log User ID',
            'share_log_lesson_id' => 'Share Log Lesson ID',
            'share_log_desc' => 'Share Log Desc',
            'share_log_status' => 'Share Log Status',
            'share_log_create_at' => 'Share Log Create At',
            'share_log_update_at' => 'Share Log Update At',
        ];
    }

    /**
     * @param int $userId
     * @param int $lessonId
     * @param string $desc
     * @param string $status
     * @return ShareLog
     * @throws DefaultException
     */
    public function create(int $userId, int $lessonId, string $desc, string $status)
    {
        $model = new self();
        $model->share_log_user_id = $userId;
        $model->share_log_lesson_id = $lessonId;
        $model->share_log_desc = $desc;
        $model->share_log_status = $status;

        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SHARE_SAVE_FAIL);
        return $model;
    }

    /**
     * @return bool
     */
    public function isSucc()
    {
        return $this->share_log_status == ShareLogConst::STATUS_SUCC;
    }
}
