<?php

namespace yii\mozayka\form;


class IpField extends ActiveField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'alias' => 'ip',
                'options' => $this->inputOptions
            ]);
        }
    }
}
