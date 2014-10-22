<?php

namespace yii\mozayka\form;

use yii\widgets\ActiveForm as YiiActiveForm;


class ActiveForm extends YiiActiveForm
{

    public $fieldClass = 'yii\mozayka\form\ActiveField';

    public $readOnly = false;

    public function field($model, $attribute, $options = [])
    {
        if ($this->readOnly) {
            $options['readOnly'] = true;
        }
        return parent::field($model, $attribute, $options);
    }
}
