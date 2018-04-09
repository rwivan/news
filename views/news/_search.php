<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\NewsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'text') ?>

    <?= $form->field($model, 'is_active')->dropDownList([
        '' => '',
        '0' => Yii::t('app', 'Inactive News'),
        '1' => Yii::t('app', 'Active News'),
    ]) ?>

    <?= $form->field($model, 'create_date_from')->widget(DatePicker::class, [
        'options'=> ['class'=>'form-control'],
		'language' => 'ru',
		'dateFormat' => 'yyyy-MM-dd',
    ]) ?>
    <?= $form->field($model, 'create_date_to')->widget(DatePicker::class, [
        'options'=> ['class'=>'form-control'],
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
