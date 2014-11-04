<?php

namespace yii\mozayka\form;

use yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TinyMceAsset,
    Yii;


class WysiwygField extends TextField
{

    public $tinyMce = [];

    public function init()
    {
        //if (!$this->readOnly) {
            $tinyMce = $this->tinyMce;
            $inputId = Html::getInputId($this->model, $this->attribute);
            $tinyMce['selector'] = '#' . $inputId;
            $js = 'jQuery(\'#' . $inputId . '\').tinymce(' . Json::encode($tinyMce) . ');';
            if (Yii::$app->getRequest()->getIsAjax()) {
                $this->template .= "\n{script}";
                $this->parts['{script}'] = Html::script($js);
            } else {
                $view = $this->form->getView();
                $view->registerJs($js);
                TinyMceAsset::register($view);
            }
        //}
        parent::init();
    }
}
