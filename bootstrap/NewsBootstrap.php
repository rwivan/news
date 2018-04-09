<?php

namespace app\bootstrap;

use app\models\News;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\db\Expression;

class NewsBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        if (! \Yii::$app->user->isGuest) {
            $user = \Yii::$app->user->getIdentity();
            if ($user->profile->send_web && $this->isShowFlash($user->profile)) {
                \Yii::$app->session->setFlash('lasted-news', \Yii::t('app', 'Has lasted news'));
            }
        }
    }

    protected function isShowFlash($profile)
    {
        if (null === $profile->lasted_news_date) {
            return true;
        }
        return News::find()
            ->andWhere(['is_active' => 1])
            ->andWhere(new Expression('create_date > :date', ['date' => $profile->lasted_news_date]))
            ->exists();
    }
}
