<?php

namespace common\models;

use Carbon\Carbon;
use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\consts\RedisConst;
use common\consts\SystemConst;
use common\exceptions\DefaultException;
use common\queries\UserQuery;
use common\services\LevelService;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $user_openid 微信的openid
 * @property string $user_name 用户昵称
 * @property string $user_phone 用户电话号码
 * @property int $user_point 用户积分
 * @property string $user_pay_status 用户支付状态
 * @property int $user_sign_num 用户签到次数
 * @property string $user_head_img 用户头像
 * @property string $user_create_at 用户创建时间
 * @property string $user_update_at 用户更新时间
 *
 * @property string $levelName  等级名字
 * @property string $rank  用户排名
 * @property int $createDays  用户创建天数
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_point', 'user_sign_num'], 'integer'],
            [['user_pay_status'], 'string'],
            [['user_create_at', 'user_update_at'], 'safe'],
            [['user_openid'], 'string', 'max' => 64],
            [['user_name'], 'string', 'max' => 128],
            [['user_phone'], 'string', 'max' => 11],
            [['user_head_img'], 'string', 'max' => 258],
            [['user_openid'], 'unique'],
            [['user_openid'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_openid' => 'User Openid',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'user_point' => 'User Point',
            'user_pay_status' => 'User Pay Status',
            'user_sign_num' => 'User Sign Num',
            'user_head_img' => 'User Head Img',
            'user_create_at' => 'User Create At',
            'user_update_at' => 'User Update At',
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @param string $openid
     * @return mixed
     */
    public function findByOpenid(string $openid)
    {
        return self::find()->byOpenid($openid)->one();
    }

    /**
     * @param string $openid
     * @param string $name
     * @param string $phone
     * @param string $headImg
     * @return User|mixed
     * @throws DefaultException
     */
    public function findOrCreate(string $openid, string $name = '', string $phone = '', string $headImg = '')
    {
        $model = (new self())->findByOpenid($openid);
        if ($model) return $model;
        $model = new self();
        $model->user_openid = $openid;
        $model->user_phone = $phone;
        $model->user_name = $name;
        $model->user_head_img = $headImg;

        if (!$model->save()) {
            Log::error(ErrorConst::msg(ErrorConst::ERROR_USER_SAVE_FAIL), [
                'message' => $model->getFirstErrors(),
                'params' => func_get_args(),
            ], LogTypeConst::TYPE_LOGIN);
            throw new DefaultException(ErrorConst::ERROR_USER_SAVE_FAIL);
        }

        return $model;
    }

    /**
     * @return User
     * @throws DefaultException
     */
    public function checkLogin()
    {
        $token = \Yii::$app->request->headers->get(SystemConst::TOKEN, '');
        if ($user = (new UserToken())->findByToken($token)->user ?? '') return $user;

        Log::warning(ErrorConst::msg(ErrorConst::ERROR_USER_NOT_LOGIN), [
            'token' => $token,
        ], LogTypeConst::TYPE_LOGIN);
        throw new DefaultException(ErrorConst::ERROR_USER_NOT_LOGIN);
    }

    /**
     * 是否已签到
     * @return bool
     */
    public function isSignToday()
    {
        return (new UserSign())->isSignToday($this->user_id);
    }

    /**
     * 进行签到
     * @return UserSign
     * @throws DefaultException
     */
    public function doSign()
    {
        return (new UserSign())->create($this->user_id);
    }

    /**
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]|self[]
     */
    public function getRankList(int $limit = 50): array
    {
        $res = self::find()->orderBy('user_point desc')->limit($limit)->all();
        return $res;
    }

    /**
     * 等级名字
     * @return string
     */
    public function getLevelName(): string
    {
        return (new LevelService())->getLevelName($this->user_point);
    }

    /**
     * 更新签到次数
     * @return bool
     * @throws DefaultException
     */
    public function updateSignNum(): bool
    {
        $this->user_sign_num += 1;
        if (!$this->save()) throw new DefaultException(ErrorConst::ERROR_USER_SIGN_NUM_UPDATE_FAIL);
        return true;
    }

    /**
     * 更新用户积分
     * @param int $point
     * @return bool
     * @throws DefaultException
     */
    public function updatePoint(int $point): bool
    {
        $this->user_point += $point;
        if (!$this->save()) throw new DefaultException(ErrorConst::ERROR_USER_POINT_UPDATE_FAIL);
        return true;
    }

    /**
     * 用户排名
     * @return int
     * @throws \yii\db\Exception
     */
    public function getRank(): int
    {
        return Yii::$app->cache->getOrSet(__METHOD__ . $this->user_id . 'rank', function () {
            $res = Yii::$app->db->createCommand("select `rank` from
          (select *, (@rowNum:=@rowNum+1) as `rank` from user, (select @rowNum:=0) as t  order by user_point desc) as t2 where user_id = {$this->user_id}
        ")->queryOne();

            return $res['rank'];
        }, RedisConst::MINUTE_ONE);
    }

    /**
     * @return int
     */
    public function getCreateDays(): int
    {
        return Carbon::now()->diffInDays(Carbon::parse($this->user_create_at)) + 1;
    }
}
