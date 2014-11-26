<?php

namespace yii\mozayka\form;


class ListBoxField extends ActiveField
{

    public $items = [];

    public function init()
    {
        if (is_callable($this->items)) {
            $callableItems = $this->items;
            $this->items = $callableItems();
        }
        parent::init();
        $this->listBox($this->items, ['prompt' => '']);
    }
}
