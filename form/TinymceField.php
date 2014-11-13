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
        $pluginOptions = $this->pluginOptions;
        if ($this->readOnly) {
            $pluginOptions = array_merge($pluginOptions, [
                'readonly' => true,
                'menubar' => false,
                'toolbar' => false,
                'statusbar' => false
            ]);
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
