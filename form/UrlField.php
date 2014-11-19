<?php

namespace yii\mozayka\form;


class UrlField extends StringField
{

    public function init()
    {
        parent::init();
        if (!$this->readOnly) {
            $this->widget('yii\widgets\MaskedInput', [
                'clientOptions' => ['alias' => 'url'],
                'options' => $this->inputOptions
            ]);
        }
    }
}
