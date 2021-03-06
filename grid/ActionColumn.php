<?php

namespace yii\mozayka\grid;

use yii\grid\ActionColumn as YiiActionColumn,
    yii\mozayka\web\DropdownAsset,
    yii\mozayka\helpers\ModelHelper,
    yii\helpers\Html,
    Yii;


class ActionColumn extends YiiActionColumn
{

    public $headerOptions = ['class' => 'hidden-print'];

    public $contentOptions = ['class' => 'action-column hidden-print'];

    public $footerOptions = ['class' => 'hidden-print'];

    public $filterOptions = ['class' => 'hidden-print'];

    public function init()
    {
        if (!Yii::$app->getRequest()->getIsAjax()) {
            DropdownAsset::register($this->grid->getView());
        }
        parent::init();
    }

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
        $dropdownId = $this->grid->getId() . '-dropdown2-menu-' . $index;
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
        $cellContent = preg_replace(array_keys($fix), array_values($fix), parent::renderDataCellContent($model, $key, $index));
        // dropdown2-menu
        if ($cellContent && ($cellContent != $this->grid->emptyCell)) {
            $cellContent = Html::button('<span class="glyphicon glyphicon-cog"></span>', [
                'title' => Yii::t('mozayka', 'Actions'),
                'class' => 'btn btn-default btn-xs',
                'data-dropdown2' => '#' . $dropdownId
            ]) . Html::tag('div', Html::tag('ul', $cellContent, ['class' => 'dropdown2-menu']), [
                'id' => $dropdownId,
                'class' => 'dropdown2 dropdown2-tip' . ((array_search($this, $this->grid->columns) + 1 > count($this->grid->columns) / 2) ? ' dropdown2-anchor-right' : '')
            ]);
        }
        return $cellContent;
    }
}
