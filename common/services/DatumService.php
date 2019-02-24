<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 5:49 PM
 */

namespace common\services;


use common\models\Datum;

class DatumService
{
    /**
     * @param int $currPage
     * @param int $pageSize
     * @return array
     */
    public function list(int $currPage, int $pageSize): array
    {
        $list = (new Datum())->list($currPage, $pageSize);
        return $list;
    }
}