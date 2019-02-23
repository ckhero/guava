<?php
return [
    'mini-program' => [
        'app_id' => 'wxbdda40128fda63c7',
        'secret' => 'bd9439f7e33977af93483ea06d9a8bba',

        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' => 'file' => __DIR__.'/../../api/runtime/log/wechat.log',
        ],
    ]
];
