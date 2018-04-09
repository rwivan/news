<?php

namespace app\helpers;

use app\models\User;

class SubscribeHelper
{
    /**
     * Сообщаем всем зарегистрированным пользователям о свежей новости.
     *
     * @param $model
     */
    public static function sendNews($model)
    {
        $userList = User::find()
            ->joinWith('profile p')
            ->andWhere('p.send_email = 1')
            ->andWhere('confirmed_at is not null')
            ->andWhere('blocked_at is null');

        foreach ($userList->each() as $user) {
            self::sendMessage($user->email, \Yii::t('app', 'Latest news'), 'new', [
                'model' => $model,
                'user' => $user,
            ]);
        }
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    protected static function sendMessage($to, $subject, $view, $params = [])
    {
        return \Yii::$app->mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
            ->setTo($to)
            ->setFrom(\Yii::$app->params['subscribeEmailFrom'])
            ->setSubject($subject)
            ->send();
    }
}
