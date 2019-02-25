<?php

namespace admin\models;

use Yii;

/**
 * This is the model class for table "admin_user_role".
 *
 * @property int $admin_user_role_id
 * @property int $admin_user_role_admin_user_id
 * @property int $admin_user_role_role_id
 * @property string $admin_user_role_create_at
 */
class AdminUserRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_user_role_admin_user_id', 'admin_user_role_role_id'], 'integer'],
            [['admin_user_role_create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_user_role_id' => 'Admin User Role ID',
            'admin_user_role_admin_user_id' => 'Admin User Role Admin User ID',
            'admin_user_role_role_id' => 'Admin User Role Role ID',
            'admin_user_role_create_at' => 'Admin User Role Create At',
        ];
    }
}
