<?php

namespace yii\mozayka\grid;


class TimeColumn extends DataColumn
{

    public $timeFormat = 'H:i:s';

    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_null($this->content)) {
            $value = $model->{$this->attribute};
            if (is_int($value)) {
                return date($this->timeFormat, $value);
            }
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
