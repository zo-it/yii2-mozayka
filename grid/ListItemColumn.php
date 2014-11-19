<?php

namespace yii\mozayka\grid;


class ListItemColumn extends DataColumn
{

    public $items = [];

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (array_key_exists($value, $this->items)) {
            return $this->items[$value];
        }
        return $value;
    }
}
