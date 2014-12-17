<?php

namespace yii\mozayka\grid;

use yii\kladovka\helpers\Text;


class DatetimeColumn extends DataColumn
{

    public $dateFormat = 'j M Y';

    public $separator = ' ';

    public $timeFormat = 'H:i:s';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value) || is_float($value) || (is_string($value) && strlen($value))) {
            return Text::date2($this->dateFormat . $this->separator . $this->timeFormat, $value);
        }
        return $value;
    }
}
