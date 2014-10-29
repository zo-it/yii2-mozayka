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
        //'altField' => '',
        'altFormat' => 'yy-mm-dd',
        'showButtonPanel' => true,
        'numberOfMonths' => 3
    ];

    public $hiddenInputOptions = [];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = Text::date($this->dateFormat, $value);
                $this->hiddenInputOptions['value'] = date('Y-m-d', $value);
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
$datePicker['altField'] = '#' . Html::getInputId($this->model, $this->attribute) . '_alt';



$this->inputOptions['name'] = false;
$this->hiddenInputOptions['id'] = Html::getInputId($this->model, $this->attribute) . '_alt';
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
