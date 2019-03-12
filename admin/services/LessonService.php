<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/13
 * Time: 12:31 AM
 */

namespace admin\services;


use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\models\Lesson;

class LessonService extends BaseService
{
    /**
     * @param $lessonType
     * @param $lessonName
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function getList($lessonType, $lessonName, $currPage, $pageSize)
    {
        list($total, $lessons) = (new Lesson())->getListByCondition($lessonType, $lessonName, $currPage, $pageSize);
        $list = [];
        $list = $lessons;
        return compact('total', 'list');
    }

    /**
     * @param $lessonId
     * @param $lessonType
     * @param $lessonName
     * @param $lessonSort
     * @return array|Lesson|null|\yii\db\ActiveRecord
     * @throws \common\exceptions\DefaultException
     */
    public function create($lessonId, $lessonType, $lessonName, $lessonSort)
    {
        if ($lessonSort <= 0 || !$lessonName) throw new DefaultException(ErrorConst::ERROR_SYSTEM_PARAMS);
        $lesson = (new Lesson())->createOrUpdate($lessonId, $lessonType, $lessonName, $lessonSort);
        return $lesson;
    }
}