<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TimePickerAsset,
    Yii;


class TimeField extends ActiveField
{

    public $timeFormat = 'H:i:s';

    public $altTimeFormat = 'H:i:s';

    public $hiddenInputOptions = [];

    public $pluginOptions = [
        'showButtonPanel' => true
    ];

    public function init()
    {
        $value = $this->model->{$this->attribute};
        if (is_int($value)) {
            $this->inputOptions['value'] = date($this->timeFormat, $value);
            $this->hiddenInputOptions['value'] = date($this->altTimeFormat, $value);
        }
        if (!$this->readOnly) {
            $pluginOptions = array_merge($this->pluginOptions, [
                'timeFormat' => Text::juiTimeFormat($this->timeFormat),
                'altTimeFormat' => Text::juiTimeFormat($this->altTimeFormat)
            ]);
            $formId = $this->form->getId();
            $inputId = Html::getInputId($this->model, $this->attribute);
            $pluginOptions['altField'] = '#' . $formId . ' #' . $inputId . '-alt';
            $js = 'jQuery(\'#' . $formId . ' #' . $inputId . '\').timepicker(' . Json::encode($pluginOptions) . ');';
            $this->inputOptions['name'] = false;
            $this->hiddenInputOptions['id'] = $inputId . '-alt';
            $this->template .= "\n{hiddenInput}";
            $this->parts['{hiddenInput}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->hiddenInputOptions);
            if (Yii::$app->getRequest()->getIsAjax()) {
                $this->template .= "\n{script}";
                $this->parts['{script}'] = Html::script($js);
            } else {
                $view = $this->form->getView();
                TimePickerAsset::register($view);
                $view->registerJs($js);
            }
        }
        parent::init();
    }
}
