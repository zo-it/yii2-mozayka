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
            $mask = $this->unsigned ? '' : '[m]';
            $mask .= str_pad('', $this->size * 3, '[9]');
            $this->widget('yii\widgets\MaskedInput', [
                'mask' => $mask,
                'definitions' => [
                    'm' => [
                        'validator' => '\\-',
                        'cardinality' => 1
                    ]
                ],
                'options' => $this->inputOptions
            ]);
        }
    }
}
