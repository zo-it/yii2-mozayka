<?php

namespace yii\mozayka\form;

use Yii;


class BooleanField extends ActiveField
{

    public function init()
    {
        parent::init();
        $this->dropDownList([
            1 => Yii::t('mozayka', 'yes'),
            0 => Yii::t('mozayka', 'no')
        ], ['prompt' => '']);
    }
}
