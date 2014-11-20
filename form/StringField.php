<?php

namespace yii\mozayka\form;


class StringField extends ActiveField
{

    public $size = 255;

    public function init()
    {
        $this->inputOptions['maxlength'] = $this->size;
        parent::init();
    }
}
