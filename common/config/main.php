<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'charset' => 'utf-8',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // ...
            ],
        ],
        'errorHandler' => [
            'class' => 'common\exceptions\ApiErrorHandler',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if (is_string($response->data)) {
                    $response->format = \yii\web\Response::FORMAT_RAW;
                } else if ($response->statusCode !== 200 && $response->statusCode !== 302) {
                    $response->format = \yii\web\Response::FORMAT_JSON;
                    if (!YII_DEBUG) {
                        $response->data = [
                            'code' => $response->statusCode,
                            'message' => $response->statusText,
                        ];
                    }
                } elseif ($response->data !== null && !is_array($response->data) && ($response->format !== 'html')) {
                    $response->format = \yii\web\Response::FORMAT_JSON;
                    $response->data = [
                        'code' => 0,
                        'data' => $response->data
                    ];
                } elseif (is_array($response->data)) {
                    $response->format = \yii\web\Response::FORMAT_JSON;
                }
            },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace', 'info'],
                    'maxFileSize' => YII_DEBUG ? 1024 * 10 : 1024 * 1024,
                    'maxLogFiles' => 5,
                    'rotateByCopy' => false,
                    'logVars' => [
                        '_GET',
                        '_POST',
                        '_FILES',
                        '_SERVER.REQUEST_URI',
                        '_SERVER.HTTP_X_TOKEN',
                        '_SERVER.HTTP_X_APP_ID',
                        '_SERVER.HTTP_X_UDID',
                        '_SERVER.SERVER_ADDR',
                        '_SERVER.REMOTE_ADDR',
                        '_SERVER.REQUEST_METHOD',
                        '_SERVER.HTTP_REFERER',
                    ],
                ],
                //dsq所有info日志收敛到这里
                [
                    'class' => 'common\log_targets\ESTarget',
                    'exportInterval' => 1,
                    'logVars' => [],//此处参数配置空，则不会输出cookie,session等内容
                    'levels' => ['info'],
                    'except' => [
                        'application',
                        'yii*',
                        'common\exception\*',
                    ],
                    'logFile' => '@app/runtime/logs/info/guava_info.log',
                    'maxFileSize' => YII_DEBUG ? 1024 * 10 : 1024 * 1024, //1GB
                    'maxLogFiles' => 5,
                    'rotateByCopy' => false,
                ],
                //dsq所有warning日志收敛到这里
                [
                    'class' => 'common\log_targets\ESTarget',
                    'exportInterval' => 1,
                    'logVars' => [],//此处参数配置空，则不会输出cookie,session等内容
                    'levels' => ['warning'],
                    'except' => [
                        'application',
                        'yii*',
                        'common\exception\*',
                    ],
                    'logFile' => '@app/runtime/logs/warning/guava_warning.log',
                    'maxFileSize' => YII_DEBUG ? 1024 * 10 : 1024 * 1024, //1GB
                    'maxLogFiles' => 5,
                    'rotateByCopy' => false,
                ],
                //dsq所有error日志收敛到这里
                [
                    'class' => 'common\log_targets\ESTarget',
                    'exportInterval' => 1,
                    'logVars' => [],//此处参数配置空，则不会输出cookie,session等内容
                    'levels' => ['error'],
                    'except' => [
                        'application',
                        'yii*',
                        'common\exception\*',
                    ],
                    'logFile' => '@app/runtime/logs/error/guava_error.log',
                    'maxFileSize' => YII_DEBUG ? 1024 * 10 : 1024 * 1024, //1GB
                    'maxLogFiles' => 5,
                    'rotateByCopy' => false,
                ],
            ],
        ],
//        'user' => [
//            'class' => 'yii\web\User',
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => false,
//        ],
    ],
];
