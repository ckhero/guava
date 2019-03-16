<?php

namespace common\models;

use common\consts\DatumConst;
use common\consts\ErrorConst;
use common\exceptions\DefaultException;
use common\queries\DatumQuery;
use simpleDI\AnotherCest;
use Yii;

/**
 * This is the model class for table "datum".
 *
 * @property int $datum_id
 * @property string $datum_type 课程资料类型[url:链接;]
 * @property string $datum_detail_type
 * @property string $datum_name 课程资料名字
 * @property string $datum_detail 课程资料内容
 * @property string $datum_create_at 课程资料添加时间
 * @property string $datum_update_at 课程资料更新时间
 */
class Datum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'datum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datum_type', 'datum_detail_type', 'datum_detail'], 'string'],
            [['datum_detail'], 'required'],
            [['datum_create_at', 'datum_update_at'], 'safe'],
            [['datum_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'datum_id' => 'Datum ID',
            'datum_type' => 'Datum Type',
            'datum_detail_type' => 'Datum Detail Type',
            'datum_name' => 'Datum Name',
            'datum_detail' => 'Datum Detail',
            'datum_create_at' => 'Datum Create At',
            'datum_update_at' => 'Datum Update At',
        ];
    }

    public static function find()
    {
        return new DatumQuery(get_called_class());
    }

    /**
     * @param int $currPage
     * @param int $pageSize
     * @param array $types
     * @return array|\yii\db\ActiveRecord[]|self[]
     */
    public function list(int $currPage, int $pageSize, array $types = [DatumConst::TYPE_DEFAULT])
    {
        $list = self::find()
            ->byTypes($types)
            ->offset(($currPage - 1) * $pageSize)
            ->limit($pageSize)
            ->orderBy('datum_id desc')
            ->all();

        return $list;
    }

    /**
     * @param $datumId
     * @param $datumName
     * @param $datumDetail
     * @param string $datumType
     * @param string $datumDetailType
     * @return Datum|null
     * @throws DefaultException
     */
    public function createOrUpdate($datumId, $datumName, $datumDetail, $datumType = DatumConst::TYPE_LESSON, $datumDetailType = DatumConst::DETAIL_TYPE_IMG)
    {
        $model = self::findOne($datumId);
        if (!$model) $model = new self();
        $model->datum_type = $datumType;
        $model->datum_detail_type = $datumDetailType;
        $model->datum_name = $datumName;
        $model->datum_detail = $datumDetail;

        if (!$model->save()) throw new DefaultException(ErrorConst::ERROR_SYSTEM_ERROR, json_encode($model->getFirstErrors(), JSON_UNESCAPED_UNICODE));
        return $model;

    }
}
