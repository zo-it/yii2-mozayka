<?php

namespace yii\mozayka\grid;


class DatetimeColumn extends DataColumn
{

    public $dateFormat = 'Y-m-d';

    public $timeFormat = 'H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return date($this->dateFormat . ' ' . $this->timeFormat, $value);
        }
        return $value;
    }
}
