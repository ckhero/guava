<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 10:55 PM
 */

namespace admin\services;


use admin\models\Role;
use admin\models\RolePrivilege;

class RoleService extends BaseService
{
    public function list()
    {
        $data = (new Role())->all();
        $list = [];
        foreach ($data as $role) {
            $privileges = [];
            foreach ($role->privileges as $privilege) {
                $privileges[] = $privilege->privilege_id;
            }
            $list[] = [
                'role_id' => $role->role_id,
                'role_name' => $role->role_name,
                'privileges' => $privileges,
            ];
        }
        return $list;
    }

    /**
     * @param string $roleName
     * @param array $privileges
     * @param int $roleId
     * @return bool
     * @throws \common\exceptions\DefaultException
     * @throws \yii\base\UserException
     */
    public function create(string $roleName, array $privileges, int $roleId, string $status)
    {
        $role = (new Role())->create($roleName, $roleId, $status);
        (new RolePrivilege())->updateRolePrivileges($role->role_id, $privileges);
        return true;
    }
}