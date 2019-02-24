<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 2:23 PM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\models\User;
use common\services\ShareService;

class ShareController extends ApiController
{
    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionSave()
    {
        $lessonId = $this->getParam('id', 0);
        $status = $this->getParam('status');
        $user = (new User())->checkLogin();

        $res = (new ShareService())->save($user, $lessonId, $status);
        return Format::success($res);
    }
}