<?php

namespace yii\mozayka\form;


class ListBoxField extends ActiveField
{

    public $items = [];

    public $multiple = false;

    public function init()
    {
        parent::init();
        $this->listBox($this->items, [
            'multiple' => $this->multiple,
            'prompt' => ''
        ]);
    }
}
