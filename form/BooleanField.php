<?php

namespace yii\mozayka\form;


class BooleanField extends ActiveField
{

    public function init()
    {
        parent::init();
        $this->checkbox();
    }
}
