<?php

namespace yii\mozayka\form;


class DecimalField extends ActiveField
{

    public $size = 10;

    public $scale = 0;

    public $unsigned = false;

    public $pluginOptions = [];

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->inputOptions['maxlength'] = $this->size + 1;
            $pluginOptions = array_merge([
                'allowPlus' => false,
                'rightAlign' => false
            ], $this->pluginOptions, [
                'alias' => 'decimal',
                'allowMinus' => !$this->unsigned,
                'integerDigits' => $this->unsigned ? ($this->size - $this->scale) : ($this->size - $this->scale - 1),
                'digits' => $this->scale
            ]);
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => $pluginOptions,
                'options' => $this->inputOptions
            ]);
        }
    }
}
