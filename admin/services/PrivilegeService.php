<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 11:34 PM
 */

namespace admin\services;


use admin\models\Privilege;

class PrivilegeService
{
    /**
     * @param int $privilegeId
     * @param int $parentId
     * @param string $code
     * @param string $text
     * @param string $detail
     * @param string $status
     * @param string $type
     * @return Privilege|null
     * @throws \common\exceptions\DefaultException
     */
    public function create(int $privilegeId, int $parentId, string $code, string $text, string $detail, string $status, string $type)
    {
        $privilege = (new Privilege())->create($privilegeId,  $parentId, $code,  $text,  $detail, $status, $type);
        return $privilege;
    }
}