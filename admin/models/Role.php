<?php

namespace admin\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $role_id
 * @property string $role_name
 * @property string $role_status
 * @property string $role_create_at
 * @property string $role_update_at
 *
 * @property Privilege[] $privileges
 * @property RolePrivilege[] $rolePrivileges
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

    /**
     * @return array|\yii\db\ActiveRecord[]|self[]
     */
    public function all()
    {
        return Role::find()->orderBy('role_id desc')->all();
    }

    /**
     * @return Privilege[]
     */
    public function getPrivileges()
    {
        $privileges = [];
        /**@var \admin\models\RolePrivilege $rolePrivilege**/
        foreach ($this->rolePrivileges as $rolePrivilege) {
            $privileges[] = $rolePrivilege->privilege;
        }
        return $privileges;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolePrivileges()
    {
        return $this->hasMany(RolePrivilege::className(), ['role_privilege_role_id' => 'role_id']);
    }

    /**
     * @param string $roleName
     * @param int $roleId
     * @return Role|null
     * @throws DefaultException
     */
    public function create(string $roleName, $roleId, $status)
    {
        $model = self::findOne($roleId);
        if (!$model) $model = new self();
        $model->role_name = $roleName;
        $model->role_status = $status;
        if (!$model->save()) {
            Log::error(ErrorConst::msg(ErrorConst::ERROR_ADMIN_ROLE_SAVE_FAIL), [func_get_args(), 'message' => $model->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
            throw new DefaultException(ErrorConst::ERROR_ADMIN_ROLE_SAVE_FAIL);
        }
        return $model;
    }
}
