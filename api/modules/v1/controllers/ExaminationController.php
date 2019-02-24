<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 11:46 PM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\models\User;
use common\services\ExaminationService;

class ExaminationController extends ApiController
{
    public function actionSubmit()
    {

    }

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionSave()
    {
        $lessonId = $this->getParam('id', 0);
        $options = $this->getParam('options', []);
        $user = (new User())->checkLogin();

        $res = (new ExaminationService())->save($user, $lessonId, $options);
        return Format::success($res);
    }
}