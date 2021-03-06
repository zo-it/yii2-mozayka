<?php

namespace yii\mozayka\grid;

use yii\kladovka\helpers\Text;


class DateColumn extends DataColumn
{

    public $dateFormat = 'j M Y';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value) || is_float($value) || (is_string($value) && strlen($value))) {
            return Text::date2($this->dateFormat, $value);
        }
        return $value;
    }
}
