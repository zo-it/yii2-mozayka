<?php

namespace yii\mozayka\form;

use Yii;


class BooleanField extends ActiveField
{

    public function init()
    {
        parent::init();
        $this->dropDownList([1 => Yii::t('mozayka', 'Yes'), 0 => Yii::t('mozayka', 'No')], ['prompt' => '']);
    }
}
