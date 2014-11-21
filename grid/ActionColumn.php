<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn,
    yii\mozayka\helpers\ModelHelper;


class ActionColumn extends YiiActionColumn
{

    public $headerOptions = ['class' => 'hidden-print'];

    public $contentOptions = ['class' => 'hidden-print'];

    public $footerOptions = ['class' => 'hidden-print'];

    public $filterOptions = ['class' => 'hidden-print'];

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
            '{view}' => ModelHelper::canRead($model),
            '{update}' => ModelHelper::canUpdate($model),
            '{delete}' => ModelHelper::canDelete($model)
        ])));
        $fix = [
            '~\s+data\-confirm\="[^"]*"~i' => '',
            '~\s+data\-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }
}
