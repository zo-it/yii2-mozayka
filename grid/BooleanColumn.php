<?php

namespace yii\mozayka\grid;

use Yii;


class BooleanColumn extends DataColumn
{

    public $format = 'html';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if ($value) {
            return '<span class="label label-success">' . Yii::t('mozayka', 'Yes') . '</span>';
        } else {
            return '<span class="label label-danger">' . Yii::t('mozayka', 'No') . '</span>';
        }
    }
}
