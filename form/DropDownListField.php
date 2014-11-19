<?php

namespace yii\mozayka\form;


class DropDownListField extends ActiveField
{

    public $items = [];

    public $multiple = false;

    public function init()
    {
        parent::init();
        $this->dropDownList($this->items, [
            'multiple' => $this->multiple,
            'prompt' => ''
        ]);
    }
}
