<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= HtmlPurifier::process($model->full) ?>
    </p>

    <p>
        <?= (new DateTime($model->create_date))->format('d.m.Y') ?>
        <?= $model->author ? $model->author->username : ''?>
    </p>
</div>
