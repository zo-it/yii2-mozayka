<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn;


class ActionColumn extends YiiActionColumn
{

    public function createUrl($action, $model, $key, $index)
    {
        switch ($action) {
            case 'view': $action = 'readForm'; break;
            case 'update': $action = 'updateForm'; break;
            case 'delete': $action = 'deleteForm'; break;
        }
        return parent::createUrl($action, $model, $key, $index);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $fix = [
            '~\s+data-confirm\="[^"]*"~i' => '',
            '~\s+data-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }
}
