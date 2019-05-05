<?php
return [
    'mini-program' => [
        'app_id' => 'wxfc3abbaf412150d2',
        'secret' => '4f1e6dc4b64bfbd9b11cb084a67c4e76',

        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' =>  __DIR__.'/../../api/runtime/logs/wechat.log',
        ],
    ],
    'uploads' => [
        'path' => '../../uploads/',
        'url' => 'http://guava.51qwer.com/uploads/',
    ],
    'domain' => [
        'site' => 'http://guava.51qwer.com',
    ],
];
