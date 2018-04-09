<?php

use app\widgets\NewsPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if (\Yii::$app->user->can('News.Create')) {
        echo '<p>';
        echo $this->render('_modal_ajax', [
            'option' => [
                'id' => 'create-news',
                'header' => \Yii::t('app', 'Create News'),
                'url' => Url::to(['create']),
                'toggleButton' => [
                    'label' => \Yii::t('app', 'Create News'),
                    'class' => 'btn btn-success',
                ],
            ]
        ]);
        echo '</p>';
    } ?>

    <?php Pjax::begin(['id' => 'list-news-pjax']) ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'layout' => "<div class='page-container'>{summary}\n{pager}<div class='clearfix'></div></div>\n{items}\n{pager}",
        'summaryOptions' => [
            'class' => 'summary',
            'style' => 'display:inline-block;float:left;margin:16px 10px 16-px 0;width:auto;',
        ],
        'pager' => [
            // Use custom pager widget class
            'class' => NewsPager::class,
            'maxButtonCount' => 4,
            'options' => [
                'class' => 'pagination',
                'style' => 'display:inline-block;float:right;margin:10px 10px 10px 0;width:auto;'
            ],
            'sizeListHtmlOptions' => [
                'class' => 'form-control',
                'style' => 'display:inline-block;float:right;margin:10px 10px 10px 0;width:auto;'
            ],
        ],
    ])?>
    <?php Pjax::end() ?>
</div>
<?= $this->render('_modal_ajax', [
    'option' => [
        'id' => 'update-news',
        'header' => \Yii::t('app', 'Update News'),
        'selector' => 'a[data-update]',
    ]
]); ?>
