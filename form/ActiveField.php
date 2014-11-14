<?php

namespace yii\mozayka\form;

use yii\bootstrap\ActiveField as YiiActiveField;


class ActiveField extends YiiActiveField
{

    public $readOnly = false;

    public $radioOptions = [];

    public $checkboxOptions = [];

    public $selectOptions = [];

    public function init()
    {
        if ($this->readOnly) {
            $this->inputOptions = array_merge($this->inputOptions, [
                'name' => false,
                'readonly' => true
            ]);
            $this->radioOptions['disabled'] = true;
            $this->checkboxOptions['disabled'] = true;
            $this->selectOptions['disabled'] = true;
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

    public function dropDownList($items, $options = [])
    {
        return parent::dropDownList($items, array_merge($this->selectOptions, $options));
    }

    public function listBox($items, $options = [])
    {
        return parent::listBox($items, array_merge($this->selectOptions, $options));
    }
}
