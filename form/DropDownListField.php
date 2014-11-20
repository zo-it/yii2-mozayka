<?php

namespace yii\mozayka\form;


class DropDownListField extends ActiveField
{

    public $items = [];

    public function init()
    {
        if (is_callable($this->items)) {
            $items = $this->items;
            $this->items = $items();
        }
        parent::init();
        $this->dropDownList($this->items, ['prompt' => '']);
    }
}
