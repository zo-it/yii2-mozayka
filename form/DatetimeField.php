<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TimePickerAsset,
    Yii;


class DatetimeField extends ActiveField
{

    public $dateFormat = 'j M Y';

    public $altDateFormat = 'Y-m-d';

    public $timeFormat = 'H:i:s';

    public $altTimeFormat = 'H:i:s';

    public $hiddenInputOptions = [];

    public $dateTimePicker = [
        'showButtonPanel' => true,
        'numberOfMonths' => 3
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = Text::date($this->dateFormat . ' ' . $this->timeFormat, $value);
                $this->hiddenInputOptions['value'] = date($this->altDateFormat . ' ' . $this->altTimeFormat, $value);
            }
        }
        if (!$this->readOnly) {
            $dateTimePicker = $this->dateTimePicker;
            if (!array_key_exists('dateFormat', $dateTimePicker)) {
                $dateTimePicker['dateFormat'] = Text::juiDateFormat($this->dateFormat);
            }
            if (!array_key_exists('altFormat', $dateTimePicker)) {
                $dateTimePicker['altFormat'] = Text::juiDateFormat($this->altDateFormat);
            }
            if (!array_key_exists('timeFormat', $dateTimePicker)) {
                $dateTimePicker['timeFormat'] = Text::juiTimeFormat($this->timeFormat);
            }
            if (!array_key_exists('altTimeFormat', $dateTimePicker)) {
                $dateTimePicker['altTimeFormat'] = Text::juiTimeFormat($this->altTimeFormat);
            }
            $inputId = Html::getInputId($this->model, $this->attribute);
            $dateTimePicker['altField'] = '#' . $inputId . '_alt';
            $dateTimePicker['altFieldTimeOnly'] = false;
            $js = 'jQuery(\'#' . $inputId . '\').datetimepicker(' . Json::encode($dateTimePicker) . ');';
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
