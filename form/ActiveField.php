<?php

namespace yii\mozayka\form;

use yii\widgets\ActiveField as YiiActiveField;


class ActiveField extends YiiActiveField
{

    public $readOnly = false;

    public function init()
    {
        parent::init();
        if ($this->readOnly) {
            $this->inputOptions['readonly'] = true;
        }
    }
}
