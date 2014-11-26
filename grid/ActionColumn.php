<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn,
    yii\mozayka\helpers\ModelHelper,
    yii\helpers\Html,
    yii\mozayka\web\DropdownAsset,
    Yii;


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
            '<li>{view}</li>' => ModelHelper::canRead($model),
            '<li>{update}</li>' => ModelHelper::canUpdate($model),
            '<li>{delete}</li>' => ModelHelper::canDelete($model)
        ])));
        $fix = [
            '~(glyphicon\-eye\-open"\>\</span\>)(\</a\>)~i' => '$1 ' . Yii::t('mozayka', 'View') . '$2',
            '~(glyphicon\-pencil"\>\</span\>)(\</a\>)~i' => '$1 ' . Yii::t('mozayka', 'Update') . '$2',
            '~(glyphicon\-trash"\>\</span\>)(\</a\>)~i' => '$1 ' . Yii::t('mozayka', 'Delete') . '$2',
            '~\s+title\="[^"]*"~i' => '',
            '~\s+data\-confirm\="[^"]*"~i' => '',
            '~\s+data\-method\="[^"]*"~i' => ''
        ];
        return preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
    }

    public function renderDataCell($model, $key, $index)
    {
        $cellContent = $this->renderDataCellContent($model, $key, $index);
        if ($cellContent && ($cellContent != $this->grid->emptyCell)) {
            $content = Html::button('<span class="glyphicon glyphicon-cog"></span>', [
                'title' => Yii::t('mozayka', 'Action'),
                'id' => 'action-trigger-' . $index,
                'class' => 'btn btn-default btn-xs',
                'data-dropdown2' => '#action-dropdown2-' . $index
            ]) . Html::tag('div', Html::tag('ul', $cellContent, ['class' => 'dropdown2-menu']), [
                'id' => 'action-dropdown2-' . $index,
                'class' => 'dropdown2 dropdown2-tip dropdown2-anchor-right'
            ]);
            if (!Yii::$app->getRequest()->getIsAjax()) {
                DropdownAsset::register($this->grid->getView());
            }
            return Html::tag('td', $content, $this->contentOptions);
        }
        return parent::renderDataCell($model, $key, $index);
    }
}
