<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/3/12
 * Time: 2:11 AM
 */

namespace admin\modules\v1\controllers;


use common\components\Format;

class MenuController extends AdminController
{
    public function actionList()
    {
        $res[] = [
            'component' => 'Layout',
            'name' => 'Excel',
            'meta' => [
                'title' => 'excel',
                'icon' => 'excel',
            ],
            'children' => [
                [
                    'path' => '/excel/exportExcel',
                    'name'=> 'ExportExcel',
                ]
            ],

        ];

        return Format::success($res);
    }
}

//{
//    path: '/excel',
//    component: Layout,
//    redirect: '/excel/export-excel',
//    name: 'Excel',
//    meta: {
//    title: 'excel',
//      icon: 'excel'
//    },
//    children: [
//      {
//          path: 'export-excel',
//        component: () => import('@/views/excel/exportExcel'),
//        name: 'ExportExcel',
//        meta: { title: 'exportExcel' }
//      },
//      {
//          path: 'export-selected-excel',
//        component: () => import('@/views/excel/selectExcel'),
//        name: 'SelectExcel',
//        meta: { title: 'selectExcel' }
//      },
//      {
//          path: 'upload-excel',
//        component: () => import('@/views/excel/uploadExcel'),
//        name: 'UploadExcel',
//        meta: { title: 'uploadExcel' }
//      }
//    ]
//  },