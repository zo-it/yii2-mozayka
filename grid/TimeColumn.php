<?php

namespace yii\mozayka\grid;


class TimeColumn extends DataColumn
{

    public $timeFormat = 'H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return date($this->timeFormat, $value);
        }
        return $value;
    }
}
