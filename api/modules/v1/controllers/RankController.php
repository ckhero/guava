<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/17
 * Time: 11:06 AM
 */

namespace api\modules\v1\controllers;


use common\services\RankService;
use common\components\ApiController;
use common\components\Format;

class RankController extends ApiController
{
    /**
     * @return array
     */
    public function actionList()
    {
        $res = (new RankService())->list();

        return Format::success($res);
    }
}