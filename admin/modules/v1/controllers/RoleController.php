<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 10:54 PM
 */

namespace admin\modules\v1\controllers;


use admin\services\RoleService;
use common\components\Format;
use common\consts\RoleConst;

class RoleController extends AdminController
{
    /**
     * @return array
     */
    public function actionList()
    {
        $list = (new RoleService())->list();
        return Format::success($list);
    }

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     * @throws \yii\base\UserException
     */
    public function actionCreate()
    {
        $roleName = $this->getParam('role_name');
        $roleId = $this->getParam('role_id', 0);
        $privileges = $this->getParam('privileges', []);
        $status = $this->getParam('status', RoleConst::STATUS_VALID);

        $res = (new RoleService())->create($roleName, $privileges, $roleId, $status);
        return Format::success($res);
    }
}