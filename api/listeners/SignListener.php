<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 3:50 AM
 */

namespace api\listeners;


use api\events\LoginEvent;
use yii\base\Exception;

/**
 * 签到
 * Class SignListener
 * @package api\listeners
 */
class SignListener
{
    /**
     * @param LoginEvent $event
     * @throws Exception
     */
    public static function handle(LoginEvent $event)
    {
        $user = $event->user;
        if (!$user->isSignToday()) {
            $tran = \Yii::$app->db->beginTransaction();
            try {
                $user->doSign();
                $user->updateSignNum();
                $tran->commit();
            } catch (Exception $e) {
                $tran->rollBack();
                throw $e;
            }
        }
    }
}