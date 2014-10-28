<?php

namespace yii\mozayka\form;

use yii\helpers\Json,
    yii\helpers\Html,
    yii\mozayka\DatePickerAsset,
    Yii;


class DateField extends ActiveField
{

    public $dateFormat = 'Y-m-d';

    public $datePicker = [
        'dateFormat' => 'yy-mm-dd',
        'numberOfMonths' => 3
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->dateFormat, $value);
                if (!$this->readOnly) {
                    $js = 'jQuery(\'#' . Html::getInputId($this->model, $this->attribute) . '\').datepicker(' . Json::encode($this->datePicker) . ');';
                    if (Yii::$app->getRequest()->getIsAjax()) {
                        $this->template .= "\n{script}";
                        $this->parts['{script}'] = Html::script($js);
                    } else {
                        $view = $this->form->getView();
                        $view->registerJs($js);
                        DatePickerAsset::register($view);
                    }
                }
            }
        }
        parent::init();
    }
}
