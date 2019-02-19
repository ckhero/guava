<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 2:18 AM
 */

namespace common\configurations;


use api\listeners\SignListener;
use common\consts\EventConst;

class EventConfiguration
{
    public static $mapEvents = [
        EventConst::EVENT_LOGIN => [
            [SignListener::class, 'handle'],
        ],
    ];

    public function loadEvents()
    {
        foreach (self::$mapEvents as $eventName => $methods) {
            foreach ($methods as $method) {
                \Yii::$app->on($eventName, $method);
            }
        }
    }
}