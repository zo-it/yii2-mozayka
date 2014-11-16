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
            $this->widget('yii\widgets\MaskedInput', [
                'mask' => str_pad($this->unsigned ? '' : '[m]', $this->size * 3, '[9]'),
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
