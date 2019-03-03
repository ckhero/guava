<?php

namespace admin\models;

use admin\queries\AdminUserQuery;
use Carbon\Carbon;
use common\components\Log;
use common\consts\AdminUserConst;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\exceptions\DefaultException;
use Yii;

/**
 * This is the model class for table "admin_user".
 *
 * @property int $admin_user_id
 * @property string $admin_user_name
 * @property string $admin_user_email
 * @property string $admin_user_is_admin
 * @property string $admin_user_status
 * @property string $admin_user_password
 * @property string $admin_user_token
 * @property string $admin_user_expire_at
 * @property string $admin_user_create_at
 * @property string $admin_user_update_at
 *
 * @property Role[] $roles
 * @property AdminUserRole[] $adminUserRoles
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_user_is_admin', 'admin_user_status'], 'string'],
            [['admin_user_expire_at', 'admin_user_create_at', 'admin_user_update_at'], 'safe'],
            [['admin_user_name'], 'string', 'max' => 32],
            [['admin_user_email'], 'string', 'max' => 21],
            [['admin_user_password', 'admin_user_token'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'admin_user_id' => 'Admin User ID',
            'admin_user_name' => 'Admin User Name',
            'admin_user_email' => 'Admin User Email',
            'admin_user_is_admin' => 'Admin User Is Admin',
            'admin_user_status' => 'Admin User Status',
            'admin_user_password' => 'Admin User Password',
            'admin_user_token' => 'Admin User Token',
            'admin_user_expire_at' => 'Admin User Expire At',
            'admin_user_create_at' => 'Admin User Create At',
            'admin_user_update_at' => 'Admin User Update At',
        ];
    }

    public static function find()
    {
        return new AdminUserQuery(get_called_class());
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int $userId
     * @return AdminUser|array|bool|null|\yii\db\ActiveRecord
     * @throws DefaultException
     * @throws \yii\base\Exception
     */
    public function create(string $name, string $email, string $password, int $userId = 0)
    {
        $model = self::findOne($userId);
        $this->isNameValid($email, $userId);
        if (!$model) $model = new self();
        $model->admin_user_name = $name;
        $model->admin_user_email = $email;
        $model->admin_user_password = Yii::$app->security->generatePasswordHash($password);
        $model->admin_user_status = AdminUserConst::STATUS_VALID;

        if (!$model->save()) {
            Log::error(ErrorConst::ERROR_ADMIN_USER_SAVE_FAIL, [func_get_args(), 'message' => $model->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
            throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_SAVE_FAIL);
        }
        return $model;
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function login(): array
    {
        $this->admin_user_token = Yii::$app->security->generateRandomString();
        $this->admin_user_expire_at = Carbon::now()->addDays(1)->toDateTimeString();
        if (!$this->save()) {
            Log::error(ErrorConst::ERROR_ADMIN_USER_SAVE_FAIL, [func_get_args(), 'message' => $this->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
            throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_SAVE_FAIL);
        }
        return [
            'token' => $this->admin_user_token,
            'expire_at' => $this->admin_user_expire_at,
        ];
    }

    /**
     * @param $token
     * @return array|null|\yii\db\ActiveRecord
     * @throws DefaultException
     */
    public function findByToken($token)
    {
        return self::find()->byToken($token)->one();
    }

    /**
     * @param string $email
     * @return array|null|\yii\db\ActiveRecord|self
     * @throws DefaultException
     */
    public function findByEmail(string $email)
    {
        return self::find()->byEmail($email)->one();
    }

    public function findByName(string $name)
    {
        return self::find()->byName($name)->one();
    }

    /**
     * @param string $password
     * @return bool
     * @throws \yii\base\Exception
     */
    public function checkPassword(string $password) {
        return Yii::$app->security->validatePassword($password, $this->admin_user_password);

    }

    /**
     * @param int $currPage
     * @param int $pageSize
     * @return array
     */
    public function list(int $currPage, int $pageSize)
    {
        $query = self::find();

        $total = $query->count();
        $list = $query->offset(($currPage - 1) * $pageSize)
            ->limit($pageSize)
            ->orderBy('admin_user_id desc')
            ->all();
        return [$total, $list];
    }

    /**
     * @param string $email
     * @param int $userId
     * @return bool
     * @throws DefaultException
     */
    public function isEmailValid(string $email, $userId = 0) {
        $model = $this->findByEmail($email);
        if ($model && $model->admin_user_id != $userId) throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_EMAIL_USED);
        return true;
    }

    /**
     * @param string $name
     * @param int $userId
     * @return bool
     * @throws DefaultException
     */
    public function isNameValid(string $name, $userId = 0) {
        $model = $this->findByName($name);
        if ($model && $model->admin_user_id != $userId) throw new DefaultException(ErrorConst::ERROR_ADMIN_USER_EMAIL_USED);
        return true;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = [];
        foreach ($this->adminUserRoles as $adminUserRole) {
            $roles[] = $adminUserRole->role;
        }
        return $roles;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUserRoles()
    {
        return $this->hasMany(AdminUserRole::className(), ['admin_user_role_admin_user_id' => 'admin_user_id']);
    }
}
