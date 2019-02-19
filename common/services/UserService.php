<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 1:19 AM
 */

namespace common\services;


use api\events\LoginEvent;
use common\consts\EventConst;
use common\models\PointLog;
use common\models\User;
use common\models\UserToken;
use Yii;

class UserService
{
    /**
     * 登陆
     * @param $openid
     * @return array
     * @throws \common\exceptions\DefaultException
     * @throws \yii\base\Exception
     */
    public function login($openid)
    {
        $user = (new User())->findOrCreate($openid);
        $userToken = (new UserToken())->createOrderUpdate($user->user_id);

        Yii::$app->trigger(EventConst::EVENT_LOGIN, (new LoginEvent($user)));
        return [
            'token' => $userToken->user_token_token,
            'expire_at' => $userToken->user_token_expire_at,
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function info(User $user):array
    {
        return [
            'name' => $user->user_name,
            'point' => $user->user_point,
            'rank' => $user->rank,
            'head_img' => $user->user_head_img,
            'level' => $user->levelName,
            'sign_day' => $user->user_sign_num
        ];
    }
}