<?php

namespace yii\mozayka\form;


class UrlField extends StringField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'alias' => 'url',
                'options' => $this->inputOptions
            ]);
        }
    }
}
