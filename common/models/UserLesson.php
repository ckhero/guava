<?php

namespace common\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\consts\UserLessonConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "user_lesson".
 *
 * @property int $user_lesson_id
 * @property int $user_lesson_user_id 用户id
 * @property int $user_lesson_score 得分
 * @property int $user_lesson_right_percent 正确率
 * @property int $user_lesson_lesson_id 学习记录课程ID
 * @property string $user_lesson_status 学习记录状态
 * @property string $user_lesson_options 选项
 * @property string $user_lesson_share_status 是否分享[init:未分享;succ:成功;fail:失败;]
 * @property string $user_lesson_create_at 学习记录创建时间
 * @property string $user_lesson_update_at 学记录更新时间
 *
 * @property User $user 用户
 * @property Lesson $lesson 课程
 */
class UserLesson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_lesson';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_lesson_user_id', 'user_lesson_right_percent', 'user_lesson_lesson_id', 'user_lesson_options'], 'required'],
            [['user_lesson_user_id', 'user_lesson_score', 'user_lesson_right_percent', 'user_lesson_lesson_id'], 'integer'],
            [['user_lesson_status', 'user_lesson_options', 'user_lesson_share_status'], 'string'],
            [['user_lesson_create_at', 'user_lesson_update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_lesson_id' => 'User Lesson ID',
            'user_lesson_user_id' => 'User Lesson User ID',
            'user_lesson_score' => 'User Lesson Score',
            'user_lesson_right_percent' => 'User Lesson Right Percent',
            'user_lesson_lesson_id' => 'User Lesson Lesson ID',
            'user_lesson_status' => 'User Lesson Status',
            'user_lesson_options' => 'User Lesson Options',
            'user_lesson_share_status' => 'User Lesson Share Status',
            'user_lesson_create_at' => 'User Lesson Create At',
            'user_lesson_update_at' => 'User Lesson Update At',
        ];
    }

    /**
     * 课程是否完成
     * @return bool
     */
    public function isFinish(): bool
    {
        return in_array($this->user_lesson_status, UserLessonConst::$mapFinish);
    }

    /**
     * @param int $userId
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord|self
     */
    public function getOne(int $userId, int $lessonId)
    {
        return self::find()->where([
            'user_lesson_user_id' => $userId,
            'user_lesson_lesson_id' => $lessonId,
        ])->one();
    }

    /**
     * @return \yii\db\ActiveQuery|User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_lesson_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery|Lesson
     */
    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['lesson_id' => 'user_lesson_lesson_id']);
    }

    /**
     * @param int $userId
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord|self
     */
    public function findByLessonId(int $userId, int $lessonId)
    {
        return self::find()->where([
            'user_lesson_user_id' => $userId,
            'user_lesson_lesson_id' => $lessonId,
        ])->one();
    }

    /**
     * @param int $userId
     * @param int $lessonId
     * @param bool $isSucc
     * @return array|UserLesson|null|\yii\db\ActiveRecord
     * @throws DefaultException
     */
    public function updateShareStatus(int $userId, int $lessonId, bool $isSucc = true)
    {
        $userLesson = (new self())->findByLessonId($userId, $lessonId);
        if (!$userLesson) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_DONE);

        if ($isSucc && $userLesson->isShare()) return $userLesson;

        $userLesson->user_lesson_share_status = UserLessonConst::SHARE_STATUS_SUCC;
        if (!$userLesson->save()) {
            Log::warning(ErrorConst::msg(ErrorConst::ERROR_USER_NOT_LOGIN), [
                'message' => $userLesson->getFirstErrors(),
            ], LogTypeConst::TYPE_SAHRE);
            throw new DefaultException(ErrorConst::ERROR_USER_LESSON_SHARE_STATUS_UPDATE_FAIL);
        }

        return $userLesson;
    }

    /**
     * @return bool
     */
    public function isShare():bool
    {
        return $this->user_lesson_share_status === UserLessonConst::SHARE_STATUS_SUCC;
    }

    /**
     * @param int $userId
     * @param int $score
     * @param int $percent
     * @param int $lessonId
     * @param array $options
     * @param string $status
     * @return array|UserLesson|null|\yii\db\ActiveRecord
     * @throws DefaultException
     */
    public function create(int $userId, int $score, int $percent, int $lessonId, array $options = [], $status = UserLessonConst::STATUS_INIT)
    {
        $model = (new self())->findByLessonId($userId, $lessonId);
        if (!$model) $model = new self();
        $model->user_lesson_user_id = $userId;
        $model->user_lesson_score = $score;
        $model->user_lesson_right_percent = $percent;
        $model->user_lesson_lesson_id = $lessonId;
        $model->user_lesson_options = json_encode($options);
        $model->user_lesson_status = $status;

        if (!$model->save()) {
            Log::warning(ErrorConst::msg(ErrorConst::ERROR_USER_LESSON_SAVE_FAIL), [
                func_get_args(),
                'message' => $model->getFirstErrors()
            ], LogTypeConst::TYPE_USER_LESSON);
            throw new DefaultException(ErrorConst::ERROR_USER_LESSON_SAVE_FAIL);
        }

        return $model;
    }
}
