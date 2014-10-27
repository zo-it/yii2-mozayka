<?php

namespace yii\mozayka\form;


class DateField extends ActiveField
{

    public $dateFormat = 'Y-m-d';

    public function init()
    {
        if (!array_key_exists('value', $this->inputOptions)) {
            $value = $this->model->{$this->attribute};
            if (is_int($value)) {
                $this->inputOptions['value'] = date($this->dateFormat, $value);
            }
        }
        parent::init();
    }
}
