<?php

namespace yii\mozayka\form;

use yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TinyMceAsset,
    Yii;


class TinymceField extends TextField
{

    public $pluginOptions = [];

    public function init()
    {
        if ($this->readOnly) {
            $pluginOptions = array_merge($this->pluginOptions, [
                'readonly' => true,
                'menubar' => false,
                'toolbar' => false,
                'statusbar' => false
            ]);
        } else {
            $pluginOptions = $this->pluginOptions;
        }
        $formId = $this->form->getId();
        $inputId = Html::getInputId($this->model, $this->attribute);
        $pluginOptions['selector'] = '#' . $formId . ' #' . $inputId;
        $js = 'jQuery(\'#' . $formId . ' #' . $inputId . '\').tinymce(' . Json::encode($pluginOptions) . ');';
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
