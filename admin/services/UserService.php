<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 12:22 AM
 */

namespace admin\services;


use admin\models\AdminUser;
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
    public function create(string $adminUserName, string $adminUserEmail, string $adminUserPassord)
    {
        if (empty($adminUserName) || empty($adminUserEmail) || empty($adminUserPassord)) throw new DefaultException(ErrorConst::ERROR_SYSTEM_PARAMS);

        $adminUser = (new AdminUser())->create($adminUserName, $adminUserEmail, $adminUserPassord);
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

        return ['total' => $total, 'list' => $list];
    }
}