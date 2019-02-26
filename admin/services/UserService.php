<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 12:22 AM
 */

namespace admin\services;


use admin\models\AdminUser;
use admin\models\AdminUserRole;
use common\consts\ErrorConst;
use common\exceptions\DefaultException;

class UserService extends BaseService
{
    /**
     * @param string $adminUserName
     * @param string $adminUserEmail
     * @param string $adminUserPassord
     * @return array
     * @throws \yii\base\Exception
     */
    public function create(string $name, string $email, string $password, array $roles = [], int $userId = 0)
    {
        if (empty($name) || empty($email) || empty($password)) throw new DefaultException(ErrorConst::ERROR_SYSTEM_PARAMS);

        $adminUser = (new AdminUser())->create($name, $email, $password, $userId);
        (new AdminUserRole())->updateAdminUserRoles($adminUser->admin_user_id, $roles);

        return $adminUser->login();
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws DefaultException
     * @throws \yii\base\Exception
     */
    public function login(string $email, string $password)
    {
        $adminUser = (new AdminUser())->findByEmail($email);
        if (!$adminUser) throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_NOT_EXISTS);

        if (!$adminUser->checkPassword($password)) throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_PASSWORD_INVALID);
        return $adminUser->login();
    }

    /**
     * @param int $currPage
     * @param int $pageSize
     * @return array
     */
    public function list(int $currPage, int $pageSize)
    {
        list($total, $list) = (new AdminUser())->list($currPage, $pageSize);
        $data = [];
        /**
         * @var $adminUser AdminUser
         */
        foreach ($list as $adminUser) {
            $roles = [];
            foreach ($adminUser->roles as $role) {
                $roles[] = $role->role_id;
            }
            $data[] = [
                'roles' => $roles,
                'admin_user_id' => $adminUser->admin_user_id,
                'admin_user_name' => $adminUser->admin_user_name,
                'admin_user_email' => $adminUser->admin_user_email,
                'admin_user_status' => $adminUser->admin_user_status,
                'roles' => $roles,
            ];
        }
        return ['total' => $total, 'list' => $data];
    }
}