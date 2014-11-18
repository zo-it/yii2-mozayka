<?php

namespace yii\mozayka\form;


class EmailField extends StringField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'alias' => 'email',
                'options' => $this->inputOptions
            ]);
        }
    }
}
