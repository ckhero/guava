<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 12:54 AM
 */

namespace admin\services;


use admin\models\AdminUser;

class BaseService
{
    /**
     * @var AdminUser
     */
    protected $adminUser;

    public function __construct(AdminUser $adminUser = null)
    {
        $this->adminUser = $adminUser;
    }
}