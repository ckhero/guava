<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 1:19 AM
 */

namespace common\services;


use api\events\LoginEvent;
use common\components\Log;
use common\consts\ErrorConst;
use common\consts\EventConst;
use common\consts\LogTypeConst;
use common\consts\SystemConst;
use common\exceptions\DefaultException;
use common\models\User;
use common\models\UserToken;
use EasyWeChat\Factory;
use Yii;

class UserService
{
    /**
     * @param string $code
     * @param string $iv
     * @param string $encryptData
     * @return array
     * @throws DefaultException
     */
    public function login(string $code, string $iv, string $encryptData)
    {
        try {
            $app = Factory::miniProgram(Yii::$app->params[SystemConst::PARAMS_CONFIG_MINI_PROGRAM]);
            $sessionInfo = $app->auth->session($code);
            $baseInfo = $app->encryptor->decryptData($sessionInfo['session_key'], $iv, $encryptData);

            $user = (new User())->findOrCreate($sessionInfo['openid'], $baseInfo['nickName'], '', $baseInfo['avatarUrl']);
            $userToken = (new UserToken())->createOrderUpdate($user->user_id);

            Yii::$app->trigger(EventConst::EVENT_LOGIN, (new LoginEvent($user)));
        } catch (\Exception $e) {
            Log::error(ErrorConst::msg(ErrorConst::ERROR_LOGIN_FAIL), [
                func_get_args(),
                'message' => $e->getMessage()
            ], LogTypeConst::TYPE_LOGIN);
            throw new DefaultException(ErrorConst::ERROR_LOGIN_FAIL);
        }

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