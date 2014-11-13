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
        $tinyMce = $this->tinyMce;
        if ($this->readOnly) {
            $tinyMce = array_merge($tinyMce, [
                'readonly' => true,
                'menubar' => false,
                'toolbar' => false,
                'statusbar' => false
            ]);
        }
        $formId = $this->form->getId();
        $inputId = Html::getInputId($this->model, $this->attribute);
        $tinyMce['selector'] = '#' . $formId . ' #' . $inputId;
        $js = 'jQuery(\'#' . $formId . ' #' . $inputId . '\').tinymce(' . Json::encode($tinyMce) . ');';
        if (Yii::$app->getRequest()->getIsAjax()) {
            $this->template .= "\n{script}";
            $this->parts['{script}'] = Html::script($js);
        } else {
            $view = $this->form->getView();
            TinyMceAsset::register($view);
            $view->registerJs($js);
        }
        parent::init();
    }
}
