<?php

namespace yii\mozayka\form;


class ListBoxField extends ActiveField
{

    public $items = [];

    public function init()
    {
        if (is_callable($this->items)) {
            $this->items = $this->items();
        }
        parent::init();
        $this->listBox($this->items, ['prompt' => '']);
    }
}
