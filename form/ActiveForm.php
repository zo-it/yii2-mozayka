<?php

namespace yii\mozayka\form;

use yii\widgets\ActiveForm as YiiActiveForm;


class ActiveForm extends YiiActiveForm
{

    public $fieldClass = 'yii\mozayka\form\ActiveField';

    public $readOnly = false;

    public function init()
    {
        if ($this->readOnly) {
            $this->fieldConfig['readOnly'] = true;
        }
        parent::init();
    }
}
