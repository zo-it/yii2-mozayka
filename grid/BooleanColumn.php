<?php

namespace yii\mozayka\grid;

use Yii;


class BooleanColumn extends DataColumn
{

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            if ($value) {
                return '<span class="label label-success">' . Yii::t('mozayka', 'yes') . '</span>';
            } else {
                return '<span class="label label-danger">' . Yii::t('mozayka', 'no') . '</span>';
            }
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}
