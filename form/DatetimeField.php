<?php

namespace yii\mozayka\form;

use yii\helpers\Json,
    yii\helpers\Html,
    yii\mozayka\TimePickerAsset,
    Yii;


class DatetimeField extends ActiveField
{

    public $dateTimeFormat = 'Y-m-d H:i:s';

    public $dateTimePicker = [
        'dateFormat' => 'yy-mm-dd',
        'timeFormat' => 'hh:mm:ss'
    ];

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->dateTimeFormat, $value);
                if (!$this->readOnly) {
                    $js = 'jQuery(\'#' . Html::getInputId($this->model, $this->attribute) . '\').datetimepicker(' . Json::encode($this->dateTimePicker) . ');';
                    if (Yii::$app->getRequest()->getIsAjax()) {
                        $this->template .= "\n{script}";
                        $this->parts['{script}'] = Html::script($js);
                    } else {
                        $view = $this->form->getView();
                        $view->registerJs($js);
                        TimePickerAsset::register($view);
                    }
                }
            }
        }
        parent::init();
    }
}
