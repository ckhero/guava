<?php

namespace admin\models;

use common\components\Log;
use common\consts\ErrorConst;
use common\consts\LogTypeConst;
use common\consts\PrivilegeConst;
use common\exceptions\DefaultException;
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
 * @property string $privilege_detail
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
            [['privilege_code', 'privilege_text'], 'string', 'max' => 64],
            [['privilege_detail'], 'string', 'max' => 256],
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
            'privilege_detail' => 'Privilege Uri',
            'privilege_create_at' => 'Privilege Create At',
            'privilege_update_at' => 'Privilege Update At',
        ];
    }

    /**
     * @param int $privilegeId
     * @param int $parentId
     * @param string $code
     * @param string $text
     * @param string $detail
     * @param string $status
     * @param string $type
     * @return Privilege|null
     * @throws DefaultException
     */
    public function create(int $privilegeId, int $parentId, string $code, string $text, string $detail, string $status = PrivilegeConst::STATUS_VALID, string $type = PrivilegeConst::TYPE_MENU)
    {
        $model = self::findOne($privilegeId);
        if (!$model) $model = new self();
        $model->privilege_parent_id = $parentId;
        $model->privilege_status = $status;
        $model->privilege_type = $type;
        $model->privilege_code = $code;
        $model->privilege_text = $text;
        $model->privilege_detail = $detail;
        if (!$model->save()) {
            Log::error(ErrorConst::msg(ErrorConst::ERROR_ADMIN_PRIVILGE_SAVE_FAIL), [func_get_args(), 'message' => $model->getFirstErrors()], LogTypeConst::TYPE_ADMIN);
            throw new DefaultException(ErrorConst::ERROR_ADMIN_PRIVILGE_SAVE_FAIL);
        }
        return $model;
    }
}
