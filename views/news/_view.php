<?php
use app\models\News;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model News */
?>
<div style="margin-top: 20px;" class="pull-right">
    <?php
    if (\Yii::$app->user->can('News.Active', ['model' => $model])) {
        $this->registerJs('
            jQuery("button[data-active]").off("click").on("click", function() {
                jQuery.ajax({
                    method: "POST", 
                    url: jQuery(this).data("url"),
                    button: this,
                    success: function (xhr) {
                        if (xhr.success) {
                            jQuery(this.button)
                                .siblings("button[data-active]").addBack()
                                .removeClass("inline hidden")
                                .filter("[data-active=" + (xhr.is_active ? "1" : "0") + "]")
                                .addClass("inline").end()
                                .filter("[data-active=" + (xhr.is_active ? "0" : "1") + "]")
                                .addClass("hidden");
                        }                    
                    }
                });
            })
        ', View::POS_READY, 'news-active');
        echo Html::button(Yii::t('app', 'Active News'), [
            'class' => 'btn btn-xs btn-success ' . ($model->is_active ? 'inline' : 'hidden'),
            'data-url' => Url::to(['inactive', 'id' => $model->id]),
            'data-active' => '1',
            'data-pjax' => '0',
        ]);
        echo Html::button(Yii::t('app', 'Inactive News'), [
            'class' => 'btn btn-xs btn-danger ' . ($model->is_active ? 'hidden' : 'inline'),
            'data-url' => Url::to(['active', 'id' => $model->id]),
            'data-active' => '0',
            'data-pjax' => '0',
        ]);
    }
    echo ' ';
    if (\Yii::$app->user->can('News.Update', ['model' => $model])) {
        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']);
        echo Html::a($icon, ['update', 'id' => $model->id], [
            'title' => Yii::t('yii', 'Update'),
            'aria-label' => Yii::t('yii', 'Update'),
            'data-update' => $model->id,
            'data-pjax' => '0',
        ]);
    }
    echo ' ';
    if (\Yii::$app->user->can('News.Delete', ['model' => $model])) {
        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']);
        echo Html::a($icon, ['delete', 'id' => $model->id], [
            'title' => Yii::t('yii', 'Delete'),
            'aria-label' => Yii::t('yii', 'Delete'),
            'data-pjax' => '0',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }
    ?>
</div>
<?php
    $option = [
        'class' => 'container highlight-text container-padding'
    ];
    if (\Yii::$app->user->can('News.View')) {
        $option['onclick'] = 'location=\'' . Url::to(['view', 'id' => $model->id]) . '\'';
    }
    echo Html::beginTag('div', $option);
?>
    <h2>
        <?= Html::encode($model->title) ?>
    </h2>
    <p class="row">
        <div class="col-xs-2">
            <?php if ($model->file) {
                echo Html::img($model->file, [
                    'class' => 'img-responsive pull-left',
                ]);
            } ?>
        </div>
        <p>
            <?= HtmlPurifier::process($model->short) ?>
        </p>
    </p>
<?php echo Html::endTag('div'); ?>
