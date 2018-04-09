<?php
namespace app\assets;

use yii\web\AssetBundle;

class NewsPagerAsset extends AssetBundle
{
    public $js = [
        'js/news-pager.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__;
        parent::init();
    }
}
