<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var dektrium\user\models\User $user
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-9',
        ],
    ],
]); ?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', $user->isNewRecord ? 'Save' : 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
