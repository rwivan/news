<?php

namespace app\mailer;

use app\models\User;
use dektrium\user\models\Token;
use yii\helpers\Url;

class Mailer extends \dektrium\user\Mailer
{
    public function sendCreateByAdminMessage(User $user, Token $token)
    {

        $mailer = $this->mailerComponent === null ? \Yii::$app->mailer : \Yii::$app->get($this->mailerComponent);
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = \Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(\Yii::$app->params['adminEmail']) ?
                \Yii::$app->params['adminEmail']
                : 'no-reply@example.com';
        }

        return $mailer->compose([
            'html' => '@app/mail/create-user-by-admin.php',
            'text' => '@app/mail/text/create-user-by-admin.php'
        ], [
            'user' => $user,
            'url' => Url::to(['/user/registration/confirm-password', 'id' => $user->id, 'code' => $token->code], true),
            'module' => $this->module,
        ])
            ->setTo($user->email)
            ->setFrom($this->sender)
            ->setSubject($this->getWelcomeSubject())
            ->send();
    }
}
