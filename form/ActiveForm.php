<?php

namespace yii\mozayka\form;

use yii\bootstrap\ActiveForm as BootstrapActiveForm,
    yii\helpers\Html;


class ActiveForm extends BootstrapActiveForm
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

    public function setInputIdPrefix($inputIdPrefix)
    {
        if (isset(Html::$inputIdPrefix)) {
            Html::$inputIdPrefix = $inputIdPrefix;
        }
    }

    public function setInputIdSuffix($inputIdSuffix)
    {
        if (isset(Html::$inputIdSuffix)) {
            Html::$inputIdSuffix = $inputIdSuffix;
        }
    }

    public function run()
    {
        parent::run();
        if (isset(Html::$inputIdPrefix)) {
            Html::$inputIdPrefix = '';
        }
        if (isset(Html::$inputIdSuffix)) {
            Html::$inputIdSuffix = '';
        }
    }

    public function fields($model, array $fields)
    {
        foreach ($fields as $attribute => $options) {
            $fields[$attribute] = $this->field($model, $attribute, $options);
        }
        return implode($fields);
    }
}
