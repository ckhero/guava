<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/24
 * Time: 5:48 PM
 */

namespace api\modules\v1\controllers;


use common\components\ApiController;
use common\components\Format;
use common\services\DatumService;

class DatumController extends ApiController
{
    /**
     * @return array
     */
    public function actionList()
    {
        $currPage = $this->getParam('curr_page', 1);
        $pageSize = $this->getParam('page_size', 20);

        $res = (new DatumService())->list($currPage, $pageSize);
        return Format::success($res);
    }
}