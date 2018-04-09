<?php
/* @var $option array */

use lo\widgets\modal\ModalAjax;
use yii\helpers\ArrayHelper;

echo ModalAjax::widget(ArrayHelper::merge([
    'ajaxSubmit' => 'false',
    'pjaxContainer' => '#list-news-pjax',
    'events' => [
        ModalAjax::EVENT_MODAL_SHOW => '
            function(data, status) {
                jQuery(this).off(\'submit\').on(\'submit\', (function(){
                    var form = jQuery(this).find(\'form\');
                    jQuery.ajax({
                        method: form.attr(\'method\'),
                        url: form.attr(\'action\'),
                        data: new FormData(form.get(0)),
                        processData: false,
                        contentType: false,
                        context: this,
                        success: function (data, status, xhr) {
                            var contentType = xhr.getResponseHeader(\'content-type\') || \'\';
                            if (contentType.indexOf(\'html\') > -1) {
                                this.injectHtml(data);
                                status = false;
                            }
                            if(status){
                                $(this).modal(\'toggle\');
                                $.pjax.reload({container : \'#list-news-pjax\'});
                            }
                        }
                    });
                    return false;
                }).bind(this));
            }
        ',
        ModalAjax::EVENT_MODAL_SUBMIT => '
            function(event, data, status, xhr) {
            }
        ',
    ]
], $option));
