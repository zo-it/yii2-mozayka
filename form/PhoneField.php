<?php

namespace yii\mozayka\form;


class PhoneField extends ActiveField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'alias' => 'phone',
                'options' => $this->inputOptions
            ]);
        }
    }
}
