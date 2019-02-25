<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "role_privilege".
 *
 * @property int $role_privilege_id
 * @property int $role_privilege_role_id
 * @property int $role_privilege_privilege_id
 * @property string $role_privilege_create_at
 */
class RolePrivilege extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_privilege';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_privilege_role_id', 'role_privilege_privilege_id'], 'required'],
            [['role_privilege_role_id', 'role_privilege_privilege_id'], 'integer'],
            [['role_privilege_create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_privilege_id' => 'Role Privilege ID',
            'role_privilege_role_id' => 'Role Privilege Role ID',
            'role_privilege_privilege_id' => 'Role Privilege Privilege ID',
            'role_privilege_create_at' => 'Role Privilege Create At',
        ];
    }
}
