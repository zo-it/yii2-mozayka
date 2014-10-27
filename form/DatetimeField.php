<?php

namespace yii\mozayka\form;


class DatetimeField extends ActiveField
{

    public $dateTimeFormat = 'Y-m-d H:i:s';

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->dateTimeFormat, $value);
            }
        }
        parent::init();
    }
}
