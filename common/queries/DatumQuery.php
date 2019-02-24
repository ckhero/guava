<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 5:52 PM
 */

namespace common\queries;


use common\consts\DatumConst;
use yii\db\ActiveQuery;

class DatumQuery extends ActiveQuery
{
    /**
     * @return DatumQuery
     */
    public function byTypes(array $types)
    {
        return $this->andOnCondition(['datum_type' => $types]);
    }
}