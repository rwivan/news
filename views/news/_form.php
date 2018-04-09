<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */

// var fff = new FormData(form.get(0));
//
//$this->registerJs('
//(function($) {
//    // сериализация данных формы с учетом передачи файлов
//    $.fn.serializefiles = function() {
//        var obj = $(this);
//        var formData = new FormData();
//        $.each($(obj).find("input[type=\'file\']"), function(i, tag) {
//            $.each($(tag)[0].files, function(i, file) {
//                formData.append(tag.name, file);
//            });
//        });
//        var params = $(obj).serializeArray();
//        $.each(params, function (i, val) {
//            formData.append(val.name, val.value);
//        });
//        return formData;
//    };
//})(jQuery);
//');
?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'short')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'full')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
