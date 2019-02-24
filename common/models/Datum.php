<?php

namespace common\models;

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
}
