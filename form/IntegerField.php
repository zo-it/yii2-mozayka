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
                'clientOptions' => [
                    'alias' => 'integer',
                    'allowPlus' => false,
                    'allowMinus' => !$this->unsigned
                ],
                'options' => $this->inputOptions
            ]);
        }
    }
}
