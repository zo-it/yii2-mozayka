<?php

namespace yii\mozayka\grid;


class DateColumn extends DataColumn
{

    public $dateFormat = 'Y-m-d';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return date($this->dateFormat, $value);
        }
        return $value;
    }
}
