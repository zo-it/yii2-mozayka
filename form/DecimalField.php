<?php

namespace yii\mozayka\form;


class DecimalField extends ActiveField
{

    public $size = 10;

    public $scale = 0;

    public $unsigned = false;

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->inputOptions['maxlength'] = $this->size + 1;
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'allowPlus' => false,
                    'allowMinus' => !$this->unsigned,
                    'integerDigits' => $this->unsigned ? ($this->size - $this->scale) : ($this->size - $this->scale - 1),
                    'digits' => $this->scale
                ],
                'options' => $this->inputOptions
            ]);
        }
    }
}
