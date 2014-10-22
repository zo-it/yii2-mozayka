<?php

namespace yii\mozayka\form;


class TextField extends ActiveField
{

    public function init()
    {
        parent::init();
        $this->textarea();
    }
}
