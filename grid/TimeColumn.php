<?php

namespace yii\mozayka\grid;

use yii\kladovka\helpers\Text;


class TimeColumn extends DataColumn
{

    public $timeFormat = 'H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value) || is_float($value) || (is_string($value) && strlen($value))) {
            return Text::date($this->timeFormat, $value);
        }
        return $value;
    }
}
