<?php

namespace common\models;

use common\consts\ErrorConst;
use common\consts\LessonConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "lesson".
 *
 * @property int $lesson_id
 * @property string $lesson_type 课程类型[english:英语;math:数学;logic:逻辑]
 * @property string $lesson_name 课程名字
 * @property int $lesson_sort 课程顺序
 * @property string $lesson_create_at 课程创建时间
 * @property string $lesson_update_at 课程更新时间
 *
 * @property string $lessonTypeText 课程类型
 * @property int $lessonTypeSort 课程排序
 * @property int $point 积分
 * @property LessonDatum $lessonDatum 课程学习资料
 * @property LessonQuestion[] $lessonQuestions 课程题目
 * @property int $questionNum 题目个数
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lesson_type'], 'string'],
            [['lesson_sort'], 'integer'],
            [['lesson_create_at', 'lesson_update_at'], 'safe'],
            [['lesson_name'], 'string', 'max' => 128],
            [['lesson_type', 'lesson_sort'], 'unique', 'targetAttribute' => ['lesson_type', 'lesson_sort']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lesson_id' => 'LessonController ID',
            'lesson_type' => 'LessonController Type',
            'lesson_name' => 'LessonController Name',
            'lesson_sort' => 'LessonController Sort',
            'lesson_create_at' => 'LessonController Create At',
            'lesson_update_at' => 'LessonController Update At',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]|self[]
     */
    public function list(): array
    {
        return self::find()->all();
    }

    /**
     * 课程类型
     * @return string
     */
    public function getLessonTypeText(): string
    {
        return LessonConst::$typeToText[$this->lesson_type];
    }

    /**
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord|self
     * @throws DefaultException
     */
    public function findByLessonId(int $lessonId)
    {
        $model = self::find()->where([
            'lesson_id' => $lessonId
        ])->one();
        if (!$model) throw new DefaultException(ErrorConst::ERROR_LESSON_NOT_EXISTS);
        return $model;
    }

    /**
     * @param int $lessonId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findByLessonIdWithoutError(int $lessonId)
    {
        $model = self::find()->where([
            'lesson_id' => $lessonId
        ])->one();
        return $model;
    }

    /**
     * @return int
     */
    public function getLessonTypeSort():int
    {
        return LessonConst::$typeSort[$this->lesson_type];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonDatum()
    {
        return $this->hasOne(LessonDatum::className(), ['lesson_datum_lesson_id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessonQuestions()
    {
        return $this->hasMany(LessonQuestion::className(), ['lesson_question_lesson_id' => 'lesson_id'])->orderBy('lesson_question_sort');
    }

    /**
     * @return bool
     * 是否需要支付
     */
    public function isNeedPay()
    {
        return $this->lesson_sort > 5;
    }

    /**
     * @return mixed
     * @throws DefaultException
     */
    public function getPoint()
    {
        switch ($this->lesson_type) {
            case LessonConst::TYPE_LOGIC:
            case LessonConst::TYPE_ENGLISH:
                $point = 2;
                break;

            case LessonConst::TYPE_MATH:
                $point = 3;
                break;

            default:
                throw new DefaultException(ErrorConst::ERROR_LESSIN_UNKOWN_TYPE);
                break;
        }
        return $point;
    }

    /**
     * @return int
     */
    public function getQuestionNum()
    {
        return count($this->lessonQuestions);
    }

    /**
     * @param $lessonType
     * @param $lessonName
     * @param $currPage
     * @param $pageSize
     * @return array
     */
    public function getListByCondition($lessonType, $lessonName, $currPage, $pageSize): array
    {
        $query = self::find();
        $query->filterWhere(['lesson_type' => $lessonType]);
        $query->andFilterWhere(['like', 'lesson_name', $lessonName]);
        $total = (int) $query->count();
        $query->offset(($currPage - 1) * $pageSize);
        $query->limit($pageSize);
        $query->orderBy('lesson_sort desc, lesson_type');
        $list = $query->all();
        return [$total, $list];
    }

    /**
     * @param $lessonId
     * @param $lessonType
     * @param $lessonName
     * @param $lessonSort
     * @return array|Lesson|null|\yii\db\ActiveRecord
     * @throws DefaultException
     */
    public function createOrUpdate($lessonId, $lessonType, $lessonName, $lessonSort)
    {
        $model = (new self())->findByLessonIdWithoutError($lessonId);
        if (!(new self())->checkTypeAndSort($lessonId, $lessonType, $lessonSort)) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, '已存在该天的课程');
        if (!$model) $model = new self();
        $model->lesson_type = $lessonType;
        $model->lesson_name = $lessonName;
        $model->lesson_sort = $lessonSort;
        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, json_encode($model->getFirstErrors(), JSON_UNESCAPED_UNICODE));
        return $model;
    }

    /**
     * @param $lessonId
     * @param $lessonType
     * @param $lessonSort
     * @return bool
     */
    public function checkTypeAndSort($lessonId, $lessonType, $lessonSort)
    {
        $model = (new self())->findByTypeAndSort($lessonType, $lessonSort);
        if (!$model) return true;
        return $model->lesson_id == $lessonId;
    }
    /**
     * @param $lessonType
     * @param $lessonSort
     * @return array|null|\yii\db\ActiveRecord|self
     */
    public function findByTypeAndSort($lessonType, $lessonSort)
    {
        return self::find()->where([
            'lesson_type' => $lessonType,
            'lesson_sort' => $lessonSort,
        ])->one();
    }
}
