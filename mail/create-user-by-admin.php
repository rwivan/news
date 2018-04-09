<?php

use app\models\User;
use yii\helpers\Html;

/* @var User $user */
/* @var string $url */
?>
    <?= Yii::t('app', 'Hello, {username}', ['username' => $user->username]) ?>.
<p>
    <?= Yii::t('app', 'Your account has been created') ?>.
</p>
<p>
    <?= Html::a(Html::encode($url), $url); ?>
</p>
<p>
    <?= Yii::t('app', 'Please click the link below to complete your registration and password set') ?>.
</p>