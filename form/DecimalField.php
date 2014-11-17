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
            $mask = str_pad($this->unsigned ? '' : '[m]', ($this->size - $this->scale) * 3, '[9]');
            if ($this->scale) {
                $mask .= '[.]' . str_pad('', $this->scale * 3, '[9]');
            }
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
