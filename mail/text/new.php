<?php

use app\models\News;
use app\models\User;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

/* @var User $user */
/* @var News $model */

?>
<?= Yii::t('app', 'Hello, {username}', ['username' => $user->username]) ?>.

<?= Yii::t('app', 'Lasted news "{title}"', ['title' => $model->title]) ?>.

<?= HtmlPurifier::process($model->short) ?>

<?= Yii::t('app', 'You can read news by link') ?>.

<?=  Url::to(['news/view', 'id' => $model->id]) ?>