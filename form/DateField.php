<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\DatePickerAsset,
    Yii;


class DateField extends ActiveField
{

    public $dateFormat = 'd M Y';

    public $datePicker = [
        //'dateFormat' => 'yy-mm-dd',
        'showButtonPanel' => true,
        'numberOfMonths' => 3
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = Text::date($this->dateFormat, $value);
            }
        }
        if (!$this->readOnly) {
            $datePicker = $this->datePicker;
            if (!array_key_exists('dateFormat', $datePicker)) {
                $datePicker['dateFormat'] = strtr($this->dateFormat, [
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
            $js = 'jQuery(\'#' . Html::getInputId($this->model, $this->attribute) . '\').datepicker(' . Json::encode($datePicker) . ');';
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
