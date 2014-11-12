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
                'mask' => ($this->unsigned ? '' : '[m]') . str_pad('', $this->size, '9'),
                'definitions' => [
                    'm' => [
                        'validator' => '\\-',
                        'cardinality' => 1
                    ]
                ]
            ]);
        }
    }
}
