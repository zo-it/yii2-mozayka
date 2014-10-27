<?php

namespace yii\mozayka\grid;


class DateColumn extends DataColumn
{

    public $dateFormat = 'Y-m-d';

    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_null($this->content)) {
            $value = $model->{$this->attribute};
            if (is_int($value)) {
                return date($this->dateFormat, $value);
            }
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
