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
        $this->setId(uniqid($this->getId()));
        if ($this->readOnly) {
            $this->fieldConfig['readOnly'] = true;
        }
        parent::init();
    }

    public function fields($model, $fields = [])
    {
        foreach ($fields as $attribute => $options) {
            $fields[$attribute] = $this->field($model, $attribute, $options);
        }
        return implode($fields);
    }
}
