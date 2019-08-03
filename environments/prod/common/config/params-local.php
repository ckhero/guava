<?php
return [
    'mini-program' => [
        'app_id' => 'wxfc3abbaf412150d2',
        'secret' => '4f1e6dc4b64bfbd9b11cb084a67c4e76',
        'mch_id' => '1533854671',
        'key' => 'b0baee9d279d34fa1dfd71aadb908c3f',
        'notify_url' => 'https://www.goodexam.com.cn/v1/order/notify',
        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' =>  __DIR__.'/../../api/runtime/logs/wechat.log',
        ],
    ],
    'uploads' => [
        'path' => '/data/www/datum/',
        'url' => 'https://www.goodexam.com.cn/',
    ],
    'domain' => [
        'site' => 'https://www.goodexam.com.cn/',
    ],
    'pay' => [
        'lessonSort' => 5,
        'price' => 6000,
    ],
];
