<?php

namespace yii\mozayka\form;

use yii\bootstrap\ActiveField as YiiActiveField;


class ActiveField extends YiiActiveField
{

    public $readOnly = false;

    public $radioOptions = [];

    public $checkboxOptions = [];

    public function init()
    {
        if ($this->readOnly) {
            $this->inputOptions['name'] = false;
            $this->inputOptions['readonly'] = true;
            $this->radioOptions['disabled'] = true;
            $this->checkboxOptions['disabled'] = true;
        }
        parent::init();
    }

    public function radio($options = [], $enclosedByLabel = true)
    {
        return parent::radio(array_merge($this->radioOptions, $options), $enclosedByLabel);
    }

    public function checkbox($options = [], $enclosedByLabel = true)
    {
        return parent::checkbox(array_merge($this->checkboxOptions, $options), $enclosedByLabel);
    }
}
