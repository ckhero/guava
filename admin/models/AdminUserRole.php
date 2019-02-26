<?php

namespace admin\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use Yii;

/**
 * This is the model class for table "admin_user_role".
 *
 * @property int $admin_user_role_id
 * @property int $admin_user_role_admin_user_id
 * @property int $admin_user_role_role_id
 * @property string $admin_user_role_create_at
 *
 * @property Role $role
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

    /**
     * @param int $userId
     * @param array $roles
     * @return bool
     */
    public function updateAdminUserRoles(int $userId, array $roles = [])
    {
        $tran = Yii::$app->db->beginTransaction();
        try {
            self::deleteAll([
                'admin_user_role_admin_user_id' => $userId,
            ]);
            $_model = new self();
            foreach ($roles as $role) {
                $model = clone $_model;
                $model->admin_user_role_admin_user_id = $userId;
                $model->admin_user_role_role_id = $role;
                $model->save();
            }
            $tran->commit();
        } catch (\Exception $e) {
            $tran->rollBack();
            Log::error(ErrorConst::msg(ErrorConst::ERROR_ADMIN_USER_ROLE_SAVE_FAIL),[func_get_args(), 'message' => $model->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['role_id' => 'admin_user_role_role_id']);
    }
}
