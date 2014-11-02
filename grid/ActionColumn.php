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
        }
        $fix = [
            '~\s+data\-confirm\="[^"]*"~i' => '',
            '~\s+data\-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }
}
