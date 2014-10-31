<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\DatePickerAsset,
    Yii;


class DateField extends ActiveField
{

    public $dateFormat = 'j M Y';

    public $altDateFormat = 'Y-m-d';

    public $hiddenInputOptions = [];

    public $datePicker = [
        'showButtonPanel' => true,
        'numberOfMonths' => 3
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = Text::date($this->dateFormat, $value);
                $this->hiddenInputOptions['value'] = date($this->altDateFormat, $value);
            }
        }
        if (!$this->readOnly) {
            $datePicker = $this->datePicker;
            if (!array_key_exists('dateFormat', $datePicker)) {
                $datePicker['dateFormat'] = Text::juiDateFormat($this->dateFormat);
            }
            if (!array_key_exists('altFormat', $datePicker)) {
                $datePicker['altFormat'] = Text::juiDateFormat($this->altDateFormat);
            }
            $inputId = Html::getInputId($this->model, $this->attribute);
            $datePicker['altField'] = '#' . $inputId . '_alt';
            $js = 'jQuery(\'#' . $inputId . '\').datepicker(' . Json::encode($datePicker) . ');';
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
                DatePickerAsset::register($view);
            }
        }
        parent::init();
    }
}
