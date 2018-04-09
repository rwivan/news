<?php

use app\commands\InitContorller;
use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

$config = ArrayHelper::merge(require __DIR__ . '/base.php', [
    'id' => 'basic-console',
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => [
                '@vendor/dektrium/yii2-user/migrations',
                '@yii/rbac/migrations',
                '@app/migrations',
            ]
        ],
        'init' => [
            'class' => InitContorller::class,
        ],
    ],
]);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
