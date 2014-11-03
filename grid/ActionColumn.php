<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn;


class ActionColumn extends YiiActionColumn
{

    public function createUrl($action, $model, $key, $index)
    {
        switch ($action) {
            case 'view': $action = 'read-form'; break;
            case 'update': $action = 'update-form'; break;
            case 'delete': $action = 'delete-form'; break;
        }
        return parent::createUrl($action, $model, $key, $index);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $this->template = implode(' ', array_keys(array_filter([
            '{view}' => is_callable([$model, 'canRead']) ? $model->canRead() : true,
            '{update}' => is_callable([$model, 'canUpdate']) ? $model->canUpdate() : true,
            '{delete}' => is_callable([$model, 'canDelete']) ? $model->canDelete() : true
        ])));
        $fix = [
            '~\s+data\-confirm\="[^"]*"~i' => '',
            '~\s+data\-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }
}
