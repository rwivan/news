<?php

namespace app\controllers;

use dektrium\user\models\Profile;
use yii\db\Expression;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionCloseLastedNews()
    {
        if (! \Yii::$app->user->isGuest) {
            \Yii::$app->session->removeFlash('lasted-news');
            Profile::updateAll(
                ['lasted_news_date' => new Expression('now()')],
                ['user_id' => \Yii::$app->user->id]
            );
        }
    }
}
