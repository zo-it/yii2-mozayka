<?php

namespace yii\mozayka\form;


class PhoneField extends StringField
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
