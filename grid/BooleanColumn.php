<?php

namespace yii\mozayka\grid;

use Yii;


class BooleanColumn extends DataColumn
{

    protected function renderDataCellContent($model, $key, $index)
    {
        if (is_null($this->content)) {
            return Yii::t('mozayka', $model->{$this->attribute} ? 'Yes' : 'No');
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }
}
