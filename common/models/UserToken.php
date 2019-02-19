<?php

namespace common\models;

use Carbon\Carbon;
use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\exceptions\DefaultException;
use common\queries\UserTokenQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_token".
 *
 * @property int $user_token_id
 * @property int $user_token_user_id 用户id
 * @property string $user_token_token 用户登陆的token
 * @property string $user_token_expire_at token过期时间
 * @property string $user_token_create_at token创建时间
 * @property string $user_token_update_at token更新时间
 *
 * @property User $user 用户
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * 过期时间
     */
    const EXPIRE_DAY = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_token_user_id'], 'required'],
            [['user_token_user_id'], 'integer'],
            [['user_token_expire_at', 'user_token_create_at', 'user_token_update_at'], 'safe'],
            [['user_token_token'], 'string', 'max' => 64],
            [['user_token_user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_token_id' => 'User Token ID',
            'user_token_user_id' => 'User Token User ID',
            'user_token_token' => 'User Token Token',
            'user_token_expire_at' => 'User Token Expire At',
            'user_token_create_at' => 'User Token Create At',
            'user_token_update_at' => 'User Token Update At',
        ];
    }

    public static function find()
    {
        return new UserTokenQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_token_user_id']);
    }

    /**
     * @param string $token
     * @return array|null|\yii\db\self
     */
    public function findByToken(string $token)
    {
        return self::find()->where(['user_token_token' => $token])
            ->isActive()
            ->one();
    }

    /**
     * @param int $userId
     * @return bool|UserToken
     * @throws DefaultException
     * @throws \yii\base\Exception
     */
    public function createOrderUpdate(int $userId)
    {
        $model = self::find()->byUserId($userId)->one();
        if (!$model) $model = new self();
        $model->user_token_user_id = $userId;
        $model->user_token_token = Yii::$app->security->generateRandomString();
        $model->user_token_expire_at = Carbon::now()->addDays(self::EXPIRE_DAY)->toDateTimeString();

        if (!$model->save()) {
            Log::error(ErrorConst::msg(ErrorConst::ERROR_USER_TOKEN_SAVE_FAIL), [
                'message' => $model->getFirstErrors(),
                'user_id' => $userId,
            ],LogTypeConst::TYPE_LOGIN);
            throw new DefaultException(ErrorConst::ERROR_USER_TOKEN_SAVE_FAIL);
        }

        return $model;
    }
}
