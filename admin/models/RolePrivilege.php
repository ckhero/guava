<?php

namespace admin\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use Yii;
use yii\base\UserException;

/**
 * This is the model class for table "role_privilege".
 *
 * @property int $role_privilege_id
 * @property int $role_privilege_role_id
 * @property int $role_privilege_privilege_id
 * @property string $role_privilege_create_at
 *
 * @property Privilege $privilege
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

    /**
     * @return mixed
     */
    public function getPrivilege()
    {
        return $this->hasOne(Privilege::className(), ['privilege_id' => 'role_privilege_privilege_id']);
    }

    /**
     * @param int $roleId
     * @param array $privileges
     * @return bool
     * @throws UserException
     */
    public function updateRolePrivileges(int $roleId, array $privileges = [])
    {
        $tran = Yii::$app->db->beginTransaction();
        try {
            self::deleteAll([
                'role_privilege_role_id' => $roleId,
            ]);
            $_model = new self();
            foreach ($privileges as $privilege) {
                $model = clone $_model;
                $model->role_privilege_role_id = $roleId;
                $model->role_privilege_privilege_id = $privilege;
                $model->save();
            }
            $tran->commit();
        } catch (\Exception $e) {
            $tran->rollBack();
            Log::error(ErrorConst::msg(ErrorConst::ERROR_ADMIN_ROLE_PRIVILGE_SAVE_FAIL),[func_get_args(), 'message' => $model->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
            throw new UserException(ErrorConst::ERROR_ADMIN_ROLE_PRIVILGE_SAVE_FAIL);
        }
        return true;
    }
}
