<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 */
?>

<?php $this->beginContent('@app/views/admin/update.php', ['user' => $user]) ?>

<?= $this->render('_form', ['user' => $user]) ?>

<?php $this->endContent() ?>
