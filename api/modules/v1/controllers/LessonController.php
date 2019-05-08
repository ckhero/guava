<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/20
 * Time: 1:39 AM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\models\User;
use common\services\LessonService;
use common\services\UserLessonService;

class LessonController extends ApiController
{
    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionToday()
    {
        $user = (new User())->checkLogin();
        $res = (new UserLessonService($user))->list($user->createDays, 3);

        return Format::success($res);
    }

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionDetail()
    {
        $lessonId = $this->getParam('id');
        $user = (new User())->checkLogin();

        $res = (new LessonService())->detail($user, $lessonId);
        return Format::success($res);
    }

    /**
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionPayStatus()
    {
        $lessonId = $this->getParam('id');
        $user = (new User())->checkLogin();

        $res = (new LessonService())->payStatus($user, $lessonId);
        return Format::success($res);
    }

    /**
     * 课程列表
     * @return array
     * @throws \common\exceptions\DefaultException
     */
    public function actionList()
    {
        $user = (new User())->checkLogin();
        $res = (new UserLessonService($user))->list();

        return Format::success($res);
    }

    public function actionReview()
    {
        $lessonId = $this->getParam('id');
        $user = (new User())->checkLogin();

        $res = (new LessonService())->detail($user, $lessonId, true);
        return Format::success($res);
    }
}