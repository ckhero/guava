<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/13
 * Time: 12:29 AM
 */

namespace admin\modules\v1\controllers;


use admin\services\LessonService;
use common\components\Format;

class LessonController extends AdminController
{
    /**
     * @return array
     */
    public function actionList()
    {
        $currPage = $this->getParam('page', 1);
        $pageSize = $this->getParam('limit', 20);
        $lessonType = $this->getParam('lesson_type');
        $lessonName = $this->getParam('lesson_name');

        $res = (new LessonService())->getList($lessonType, $lessonName, $currPage, $pageSize);
        return Format::success($res);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        $lessonId = (int) $this->getParam('lesson_id');
        $lessonSort = (int) $this->getParam('lesson_sort');
        $lessonType = $this->getParam('lesson_type');
        $lessonName = $this->getParam('lesson_name');
        $datum = $this->getParam('datum');
        $questions = $this->getParam('questions');

        $res = (new LessonService())->create($lessonId, $lessonType, $lessonName, $lessonSort, $datum, $questions);
        return Format::success($res);
    }
}