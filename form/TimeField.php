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

    public $pluginOptions = [];

    public function init()
    {
        $value = $this->model->{$this->attribute};
        if (is_int($value) || is_float($value) || (is_string($value) && strlen($value))) {
            $this->inputOptions['value'] = Text::date($this->timeFormat, $value);
            $this->hiddenInputOptions['value'] = Text::date($this->altTimeFormat, $value);
        }
        if (!$this->readOnly) {
            $pluginOptions = array_merge(['showButtonPanel' => true], $this->pluginOptions, [
                'timeFormat' => Text::juiTimeFormat($this->timeFormat),
                'altTimeFormat' => Text::juiTimeFormat($this->altTimeFormat)
            ]);
            $inputId = Html::getInputId($this->model, $this->attribute);
            $pluginOptions['altField'] = '#' . $inputId . '-alt';
            $js = 'jQuery(\'#' . $inputId . '\').timepicker(' . Json::encode($pluginOptions) . ');';
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
