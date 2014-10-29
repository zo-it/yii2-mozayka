<?php

namespace yii\mozayka\grid;

use yii\kladovka\helpers\Text;


class DateColumn extends DataColumn
{

    public $dateFormat = 'd M Y';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return Text::date($this->dateFormat, $value);
        }
        return $value;
    }
}
