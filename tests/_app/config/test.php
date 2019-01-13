<?php

return [
    'id' => 'yii2-user-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@SomeBlackMagic/Yii2User' => dirname(dirname(dirname(__DIR__))),
        '@tests' => dirname(dirname(__DIR__)),
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower-asset',
    ],
    'bootstrap' => [
        \SomeBlackMagic\Yii2User\Bootstrap::class
    ],
    'modules' => [
        'user' => [
            'class' => \SomeBlackMagic\Yii2User\Module::class,
            'admins' => ['user'],
        ],
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'log'              => [
            'logger' => [
                'class' => \Codeception\Lib\Connector\Yii2\Logger::class,
                'traceLevel'  => 3,
            ]
        ],
    ],
    'params' => [],
];
