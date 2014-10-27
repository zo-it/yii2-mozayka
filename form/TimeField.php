<?php

namespace yii\mozayka\form;


class TimeField extends ActiveField
{

    public $timeFormat = 'H:i:s';

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->timeFormat, $value);
            }
        }
        parent::init();
    }
}
