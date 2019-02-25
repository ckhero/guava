<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $role_id
 * @property string $role_name
 * @property string $role_status
 * @property string $role_create_at
 * @property string $role_update_at
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_status'], 'string'],
            [['role_create_at', 'role_update_at'], 'safe'],
            [['role_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'role_name' => 'Role Name',
            'role_status' => 'Role Status',
            'role_create_at' => 'Role Create At',
            'role_update_at' => 'Role Update At',
        ];
    }
}
