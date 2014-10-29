<?php

namespace yii\mozayka\grid;

use yii\mozayka\helpers\Text;


class DatetimeColumn extends DataColumn
{

    public $dateFormat = 'd M Y';

    public $timeFormat = 'H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return Text::date($this->dateFormat . ' ' . $this->timeFormat, $value);
        }
        return $value;
    }
}
