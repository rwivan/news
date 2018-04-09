<?php

use app\models\User;
use dektrium\user\models\Token;
use yii\helpers\Html;

/* @var User $user */
/* @var string $url */
?>
<?= Yii::t('app', 'Hello, {username}', ['username' => $user->username]) ?>.

<?= Yii::t('app', 'Your account has been created') ?>.

<?= $url; ?>

<?= Yii::t('app', 'Please click the link below to complete your registration and password set') ?>.
