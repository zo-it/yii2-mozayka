<?php

namespace yii\mozayka\form;

use yii\bootstrap\ActiveForm as YiiActiveForm;


class ActiveForm extends YiiActiveForm
{

    public $fieldClass = 'yii\mozayka\form\ActiveField';

    public $enableClientValidation = false;

    public $enableAjaxValidation = true;

    public $validateOnChange = false;

    public $validateOnBlur = false;

    public $readOnly = false;

    public function init()
    {
        if ($this->readOnly) {
            $this->fieldConfig['readOnly'] = true;
        }
        parent::init();
    }
}
