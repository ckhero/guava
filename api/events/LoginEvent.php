<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 3:49 AM
 */

namespace api\events;


use common\models\User;
use yii\base\Event;

class LoginEvent extends Event
{
    public function __construct(User $user)
    {
        parent::__construct([]);
        $this->user = $user;
    }

    /**
     * @var User
     */
    public $user;
}