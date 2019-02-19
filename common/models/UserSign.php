<?php

namespace common\models;

use Carbon\Carbon;
use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\exceptions\DefaultException;

/**
 * This is the model class for table "user_sign".
 *
 * @property int $user_sign_id
 * @property int $user_sign_user_id 用户id
 * @property string $user_sign_sign_at 签到时间
 */
class UserSign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_sign_user_id'], 'integer'],
            [['user_sign_sign_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_sign_id' => 'User Sign ID',
            'user_sign_user_id' => 'User Sign User ID',
            'user_sign_sign_at' => 'User Sign Sign At',
        ];
    }

    /**
     * 是否已签到
     * @param int $userId
     * @return bool
     */
    public function isSignToday(int $userId)
    {
        $model = self::find()->where([
            'user_sign_user_id' => $userId,
        ])->orderBy('user_sign_id desc')->one();
        return $model && Carbon::parse($model->user_sign_sign_at)->diffInDays(Carbon::now()) === 0;
    }

    /**
     * @param int $userId
     * @return UserSign
     * @throws DefaultException
     */
    public function create(int $userId)
    {
        $model = new self();
        $model->user_sign_user_id = $userId;
        $model->user_sign_sign_at = Carbon::now();

        if (!$model->save()) {
            Log::info(ErrorConst::msg(ErrorConst::ERROR_USER_SIGN_SAVE_FAIL, [
                'message' => $model->getFirstErrors(),
                'user_id' => $userId,
            ]), LogTypeConst::TYPE_LOGIN);
            throw new DefaultException(ErrorConst::ERROR_USER_SIGN_SAVE_FAIL);
        }
        return $model;
    }
}
