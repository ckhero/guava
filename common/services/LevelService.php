<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 11:32 AM
 */

namespace common\services;


use common\models\Level;

/**
 * 等级
 * Class LevelService
 * @package common\services
 */
class LevelService
{
    /**
     * 获取等级名字
     * @param int $point
     * @return string
     */
    public function getLevelName(int $point): string
    {
        $levelList = (new Level())->list();

        foreach ($levelList as $level) {
            if ($point >= $level->level_min_point && ($point < $level->level_max_point || $level->level_max_point == 0)) {
                return $level->level_name;
            }
        }
    }
}