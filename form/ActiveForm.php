<?php

namespace yii\mozayka\form;

use yii\widgets\ActiveForm as YiiActiveForm;


class ActiveForm extends YiiActiveForm
{

    public function field($model, $attribute, $options = [])
    {
        if (!array_key_exists('class', $options)) {
            //$options['class'] = '';
        }
        return parent::field($model, $attribute, $options);
    }
}
