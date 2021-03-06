<?php

namespace yii\mozayka\form;


class IpField extends StringField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => ['alias' => 'ip'],
                'options' => $this->inputOptions
            ]);
        }
    }
}
