<?php

use app\models\News;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var User $user */
/* @var News $model */

?>
<p>
    <?= Yii::t('app', 'Hello, {username}', ['username' => $user->username]) ?>.
</p>
<p>
    <?= Yii::t('app', 'Lasted news "{title}"', ['title' => $model->title]) ?>.
</p>

<?= HtmlPurifier::process($model->short) ?>

<p>
    <?= Html::a(Yii::t('app', 'read news'), Url::to([
        'news/view',
        'id' => $model->id,
    ], true)); ?>
</p>
