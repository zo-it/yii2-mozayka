<?php

namespace yii\mozayka\form;


class TextField extends ActiveField
{

    public function init()
    {
        if (!array_key_exists('style', $this->inputOptions)) {
            $this->inputOptions['style'] = 'height: 100px;';
        }
        parent::init();
        $this->textarea();
    }
}
