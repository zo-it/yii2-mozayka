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

    public $timePicker = [
        'showButtonPanel' => true
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->timeFormat, $value);
                $this->hiddenInputOptions['value'] = date($this->altTimeFormat, $value);
            }
        }
        if (!$this->readOnly) {
            $timePicker = $this->timePicker;
            if (!array_key_exists('timeFormat', $timePicker)) {
                $timePicker['timeFormat'] = Text::juiTimeFormat($this->timeFormat);
            }
            if (!array_key_exists('altTimeFormat', $timePicker)) {
                $timePicker['altTimeFormat'] = Text::juiTimeFormat($this->altTimeFormat);
            }
            $inputId = Html::getInputId($this->model, $this->attribute);
            $timePicker['altField'] = '#' . $inputId . '_alt';
            $js = 'jQuery(\'#' . $inputId . '\').timepicker(' . Json::encode($timePicker) . ');';
            $this->inputOptions['name'] = false;
            $this->hiddenInputOptions['id'] = $inputId . '_alt';
            $this->template .= "\n{hiddenInput}";
            $this->parts['{hiddenInput}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->hiddenInputOptions);
            if (Yii::$app->getRequest()->getIsAjax()) {
                $this->template .= "\n{script}";
                $this->parts['{script}'] = Html::script($js);
            } else {
                $view = $this->form->getView();
                $view->registerJs($js);
                TimePickerAsset::register($view);
            }
        }
        parent::init();
    }
}
