<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "privilege".
 *
 * @property int $privilege_id
 * @property int $privilege_parent_id
 * @property string $privilege_status
 * @property string $privilege_type
 * @property string $privilege_code
 * @property string $privilege_text
 * @property string $privilege_uri
 * @property string $privilege_create_at
 * @property string $privilege_update_at
 */
class Privilege extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'privilege';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['privilege_parent_id'], 'integer'],
            [['privilege_status', 'privilege_type'], 'string'],
            [['privilege_create_at', 'privilege_update_at'], 'safe'],
            [['privilege_code', 'privilege_text', 'privilege_uri'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'privilege_id' => 'Privilege ID',
            'privilege_parent_id' => 'Privilege Parent ID',
            'privilege_status' => 'Privilege Status',
            'privilege_type' => 'Privilege Type',
            'privilege_code' => 'Privilege Code',
            'privilege_text' => 'Privilege Text',
            'privilege_uri' => 'Privilege Uri',
            'privilege_create_at' => 'Privilege Create At',
            'privilege_update_at' => 'Privilege Update At',
        ];
    }
}
