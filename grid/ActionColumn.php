<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn,
    yii\mozayka\db\ActiveRecord;


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
        if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
            $this->template = implode(' ', array_keys(array_filter([
                '{view}' => $model->canRead(),
                '{update}' => $model->canUpdate(),
                '{delete}' => $model->canDelete()
            ])));
        } else {
            $this->template = implode(' ', array_keys(array_filter([
                '{view}' => method_exists($model, 'canRead') && is_callable([$model, 'canRead']) ? $model->canRead() : (bool)$model::primaryKey(),
                '{update}' => method_exists($model, 'canUpdate') && is_callable([$model, 'canUpdate']) ? $model->canUpdate() : (bool)$model::getTableSchema()->primaryKey,
                '{delete}' => method_exists($model, 'canDelete') && is_callable([$model, 'canDelete']) ? $model->canDelete() : (bool)$model::getTableSchema()->primaryKey
            ])));
        }
        $fix = [
            '~\s+data\-confirm\="[^"]*"~i' => '',
            '~\s+data\-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }
}
