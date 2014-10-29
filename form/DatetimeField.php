<?php

namespace yii\mozayka\form;

use yii\mozayka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TimePickerAsset,
    Yii;


class DatetimeField extends ActiveField
{

    public $dateFormat = 'd M Y';

    public $timeFormat = 'H:i:s';

    public $dateTimePicker = [
        //'dateFormat' => 'yy-mm-dd',
        //'timeFormat' => 'hh:mm:ss',
        'showButtonPanel' => true,
        'numberOfMonths' => 3
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = Text::date($this->dateFormat . ' ' . $this->timeFormat, $value);
            }
        }
        if (!$this->readOnly) {
            $dateTimePicker = $this->dateTimePicker;
            if (!array_key_exists('dateFormat', $dateTimePicker)) {
                $dateTimePicker['dateFormat'] = strtr($this->dateFormat, [
                    'Y' => 'yy',
                    //'y' => 'y',
                    'F' => 'MM',
                    //'M' => 'M',
                    'm' => 'mm',
                    'n' => 'm',
                    'l' => 'DD',
                    'D' => 'D',
                    'd' => 'dd',
                    'j' => 'd'
                ]);
            }
            if (!array_key_exists('timeFormat', $dateTimePicker)) {
                $dateTimePicker['timeFormat'] = strtr($this->timeFormat, [
                    'H' => 'hh',
                    'G' => 'h',
                    'i' => 'mm',
                    's' => 'ss'
                ]);
            }
            $js = 'jQuery(\'#' . Html::getInputId($this->model, $this->attribute) . '\').datetimepicker(' . Json::encode($dateTimePicker) . ');';
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
