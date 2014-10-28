<?php

namespace yii\mozayka\form;

use yii\helpers\Json,
    yii\helpers\Html,
    yii\mozayka\TimePickerAsset,
    Yii;


class TimeField extends ActiveField
{

    public $timeFormat = 'H:i:s';

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->timeFormat, $value);
                if (!$this->readOnly) {
                    $timePicker = [
                        'timeFormat' => 'hh:mm:ss'
                    ];
                    $js = 'jQuery(\'#' . Html::getInputId($this->model, $this->attribute) . '\').timepicker(' . Json::encode($timePicker) . ');';
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
