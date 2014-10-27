<?php

namespace yii\mozayka\grid;


class DatetimeColumn extends DataColumn
{

    public $dateTimeFormat = 'Y-m-d H:i:s';

    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_null($this->content)) {
            $value = $model->{$this->attribute};
            if (is_int($value)) {
                return date($this->dateTimeFormat, $value);
            }
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
