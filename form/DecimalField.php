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
        $this->widget('yii\widgets\MaskedInput', [
            'mask' => ($this->unsigned ? '' : '[m]') . str_pad('', ($this->size - $this->scale), '9') . ($this->scale ? ('[.' . str_pad('', $this->scale, '9') . ']') : ''),
            'definitions' => [
                'm' => [
                    'validator' => '\\-',
                    'cardinality' => 1
                ]
            ]
        ]);
    }
}
