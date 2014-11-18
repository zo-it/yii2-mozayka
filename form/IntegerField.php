<?php

namespace yii\mozayka\form;


class IntegerField extends ActiveField
{

    public $size = 10;

    public $unsigned = false;

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->inputOptions['maxlength'] = $this->size;
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => [
                    'alias' => 'integer',
                    'allowPlus' => false,
                    'allowMinus' => !$this->unsigned,
                    'integerDigits' => $this->unsigned ? $this->size : ($this->size - 1)
                ],
                'options' => $this->inputOptions
            ]);
        }
    }
}
