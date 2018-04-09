<?php

namespace app\controllers;

use dektrium\user\controllers\RecoveryController as RecoveryControllerBase;
use yii\filters\AccessControl;

class RecoveryController extends RecoveryControllerBase
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true,
                     'actions' => [
                         'request',
                         'reset',
                     ],
                     'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }
}
