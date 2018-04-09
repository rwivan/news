<?php

use app\bootstrap\NewsBootstrap;
use app\controllers\AdminController;
use app\controllers\RecoveryController;
use app\controllers\RegistrationController;
use app\models\User;
use dektrium\user\controllers\SettingsController;
use yii\helpers\ArrayHelper;

$config = ArrayHelper::merge(require __DIR__ . '/base.php', [
    'id' => 'basic',
    'name' => 'Тестовое задание',
    'bootstrap' => [
        NewsBootstrap::class,
    ],
    'defaultRoute' => 'news',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'controllerMap' => [
                'admin' => [
                    'class' => AdminController::class,
                    'viewPath' => '@app/views/admin/',
                ],
                'settings' => [
                    'class' => SettingsController::class,
                    'viewPath' => '@app/views/settings/',
                ],
                'registration' => [
                    'class' => RegistrationController::class,
                ],
                'recovery' => [
                    'class' => RecoveryController::class,
                ]
            ],
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '0QEeb25SAU5VtF9LcADFQardBXAiYiPsI9qDtdpfJwDWuSx7S8djHmImgWTf3PaBgCyJ8AmLYGfvpIYRWiG',
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
]);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.37.150', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.37.150', '*'],
    ];
}

return $config;
