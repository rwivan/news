<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label' => Yii::t('user', 'Users'),
            'url' => ['/user/admin/index'],
        ],
        [
            'label' => Yii::t('user', 'Roles'),
            'url' => ['/rbac/role/index'],
            'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        ],
        [
            'label' => Yii::t('user', 'Permissions'),
            'url' => ['/rbac/permission/index'],
            'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        ],
        [
            'label' => Yii::t('user', 'Create'),
            'items' => [
                [
                    'label' => Yii::t('user', 'New user'),
                    'id' => 'nav-user-create',
                    'url' => '#',
                    'options' => ['data-toggle' => 'modal', 'data-target' => '#create-user'],
                    'visible' => 'create' !== Yii::$app->controller->action->id,
                ],
                [
                    'label' => Yii::t('user', 'New user'),
                    'linkOptions' => [
                        'onclick' => 'jQuery("#user-create").modal("show");',
                    ],
                    'url' => ['/user/admin/create'],
                    'visible' => 'update' === Yii::$app->controller->action->id,
                ],
                [
                    'label' => Yii::t('user', 'New role'),
                    'url' => ['/rbac/role/create'],
                    'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                ],
                [
                    'label' => Yii::t('user', 'New permission'),
                    'url' => ['/rbac/permission/create'],
                    'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
                ],
            ],
        ],
    ],
]) ?>
