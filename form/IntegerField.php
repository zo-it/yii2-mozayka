<?php

namespace yii\mozayka\form;


class IntegerField extends ActiveField
{

    public $size = 10;

    public $unsigned = false;

    public $pluginOptions = [];

    public function init()
    {
        $this->inputOptions['maxlength'] = $this->size;
        parent::init();
        if (!$this->readOnly) {
            $pluginOptions = array_merge([
                'allowPlus' => false,
                'rightAlign' => false
            ], $this->pluginOptions, [
                'alias' => 'integer',
                'allowPlus' => false,
                'allowMinus' => !$this->unsigned,
                'integerDigits' => $this->unsigned ? $this->size : ($this->size - 1)
            ]);
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => $pluginOptions,
                'options' => $this->inputOptions
            ]);
        }
    }
}
