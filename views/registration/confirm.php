<?php

/**
 * @var yii\web\View $this
 * @var dektrium\user\Module $module
 * @var string $title
 * @var User $user
 */

use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'password-recovery-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= Html::submitButton(Yii::t('user', 'Finish'), ['class' => 'btn btn-success btn-block']) ?><br>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
