<?php
namespace app\widgets;

use app\assets\NewsPagerAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\LinkPager;

class NewsPager extends LinkPager
{
    private $pageSizeList = [5, 10, 20, 30];

    public $sizeListHtmlOptions = [];

    public function getPageParam()
    {
        return $this->pagination->pageParam;
    }

    public function getPageSizeParam()
    {
        return $this->pagination->pageSizeParam;
    }

    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }

        NewsPagerAsset::register($this->getView());
        $jsOptions = [
            'pageParam' => $this->getPageParam(),
            'pageSizeParam' => $this->getPageSizeParam(),
            'url' => $this->pagination->createUrl($this->pagination->getPage())
        ];
        $this->getView()->registerJs("newsPagerWidget.init(" . Json::encode($jsOptions) . ");");

        return $this->renderPageSizeList() . $this->renderPageButtons();
    }

    private function renderPageSizeList()
    {
        return Html::dropDownList($this->getPageSizeParam(),
            $this->pagination->getPageSize(),
            array_combine($this->pageSizeList, $this->pageSizeList),
            $this->sizeListHtmlOptions
        );
    }
}
