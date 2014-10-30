<?php

namespace yii\mozayka\form;


class BooleanField extends ActiveField
{

    public function init()
    {
        if (!array_key_exists('style', $this->labelOptions)) {
            $this->labelOptions['style'] = 'font-weight: bold;';
        }
        parent::init();
        $this->checkbox();
    }
}
