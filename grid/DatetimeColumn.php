<?php

namespace yii\mozayka\grid;


class DatetimeColumn extends DataColumn
{

    public $dateTimeFormat = 'Y-m-d H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return date($this->dateTimeFormat, $value);
        }
        return $value;
    }
}
