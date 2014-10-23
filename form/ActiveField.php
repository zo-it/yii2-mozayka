<?php

namespace yii\mozayka\form;

use yii\widgets\ActiveField as YiiActiveField;


class ActiveField extends YiiActiveField
{

    public $readOnly = false;

    public $radioOptions = [];

    public $checkboxOptions = [];

    public function init()
    {
        if ($this->readOnly) {
            $this->inputOptions['readonly'] = true;
            $this->radioOptions['disabled'] = true;
            $this->checkboxOptions['disabled'] = true;
        }
        parent::init();
    }

    public function radio($options = [], $enclosedByLabel = true)
    {
        $options = array_merge($this->radioOptions, $options);
        return parent::radio($options, $enclosedByLabel);
    }

    public function checkbox($options = [], $enclosedByLabel = true)
    {
        $options = array_merge($this->checkboxOptions, $options);
        return parent::checkbox($options, $enclosedByLabel);
    }
}
