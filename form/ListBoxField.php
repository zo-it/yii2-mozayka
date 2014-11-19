<?php

namespace yii\mozayka\form;


class ListBoxField extends ActiveField
{

    public $items = [];

    public function init()
    {
        parent::init();
        $this->listBox($this->items, ['prompt' => '']);
    }
}
