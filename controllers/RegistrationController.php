<?php

namespace app\controllers;

use dektrium\user\controllers\RegistrationController as RegistrationControllerBase;
use dektrium\user\models\Token;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Class RegistrationController.
 * Контроллера обрабатывающий запросы на регистрацию пользователей
 *
 * @package app\controllers
 */
class RegistrationController extends RegistrationControllerBase
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow'   => true,
                     'actions' => [
                         'register',
                         'connect',
                     ],
                     'roles'   => ['?'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => [
                            'confirm',
                            'confirm-password',
                            'resend',
                        ],
                        'roles'   => [
                            '?',
                            '@',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionConfirmPassword($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException();
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_CONFIRM, $event);

        $user->attemptConfirmation($code);

        $this->trigger(self::EVENT_AFTER_CONFIRM, $event);

        /** @var Token $token */
        $token = \Yii::createObject([
            'class' => Token::className(),
            'user_id' => $user->id,
            'type' => Token::TYPE_RECOVERY,
        ]);

        if (!$token->save(false)) {
            return $this->render('/message', [
                'title'  => \Yii::t('app', 'Cannot save token'),
                'module' => $this->module,
            ]);
        }
        $this->redirect($token->url);
    }
}