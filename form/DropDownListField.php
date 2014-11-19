<?php

namespace yii\mozayka\form;


class DropDownListField extends ActiveField
{

    public $items = [];

    public function init()
    {
        parent::init();
        $this->dropDownList($this->items, ['prompt' => '']);
    }
}
