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
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'allowPlus' => false,
                    'allowMinus' => !$this->unsigned,
                    'digits' => $this->scale
                ],
                'options' => $this->inputOptions
            ]);
        }
    }
}
