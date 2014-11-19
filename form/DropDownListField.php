<?php

namespace yii\mozayka\form;


class DropDownListField extends ActiveField
{

    public $items = [];

    public function init()
    {
        if (is_callable($this->items)) {
            $this->items = $this->items();
        }
        parent::init();
        $this->dropDownList($this->items, ['prompt' => '']);
    }
}
