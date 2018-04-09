<?php

namespace app\models;

use app\mailer\Mailer;
use dektrium\user\helpers\Password;
use dektrium\user\models\Token;
use dektrium\user\models\User AS BaseUser;

/**
 * Class User.
 * Модель пользователя.
 *
 * @property-read Mailer $mailer
 *
 * @package app\models
 */
class User extends BaseUser
{
    /**
     * Создание пользователя администратором.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function createByAdmin()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $transaction = $this->getDb()->beginTransaction();

        try {
            $this->password = $this->password == null ? Password::generate(8) : $this->password;

            $this->trigger(self::BEFORE_CREATE);

            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            /** @var Token $token */
            $token = \Yii::createObject(['class' => Token::class, 'type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);

            $this->mailer->sendCreateByAdminMessage($this, $token);
            $this->trigger(self::AFTER_CREATE);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::warning($e->getMessage());
            throw $e;
        }
    }
}
