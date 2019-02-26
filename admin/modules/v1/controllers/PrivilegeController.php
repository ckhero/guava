<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/26
 * Time: 11:48 PM
 */

namespace admin\modules\v1\controllers;


use admin\services\PrivilegeService;
use common\components\Format;
use common\consts\PrivilegeConst;

class PrivilegeController extends AdminController
{
    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionCreate()
    {
        $privilegeId = $this->getParam('privilege_id', 0);
        $parentId = $this->getParam('parent_id', 0);
        $code = $this->getParam('code');
        $text = $this->getParam('text');
        $detail = $this->getParam('detail');
        $status = $this->getParam('status', PrivilegeConst::STATUS_VALID);
        $type = $this->getParam('type', PrivilegeConst::TYPE_MENU);

        $res = (new PrivilegeService())->create($privilegeId,  $parentId, $code,  $text,  $detail, $status, $type);
        return Format::success($res);
    }
}