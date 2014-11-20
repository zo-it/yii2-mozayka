<?php

namespace yii\mozayka\grid;


class ListItemColumn extends DataColumn
{

    public $items = [];

    public function init()
    {
        if (is_callable($this->items)) {
            $items = $this->items;
            $this->items = $items();
        }
        parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (array_key_exists($value, $this->items)) {
            return $this->items[$value];
        }
        return $value;
    }
}
