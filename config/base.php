<?php

use app\mailer\Mailer;
use dektrium\rbac\components\DbManager;

return [
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'mailer' => [
                'class' => Mailer::class,
            ]
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2basic_news',
            'username' => 'news_maker',
            'password' => 'WxnYMHWymstn6V9Sc99gaDIS4ytwtY3b',
            'charset' => 'utf8',
            'tablePrefix' => 'r_',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'authManager' => [
            'class' => DbManager::class,
            'cache' => 'cache',
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@news.com',
        'subscribeEmailFrom' => 'no-reply@news.com',
    ],
];
