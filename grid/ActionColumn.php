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

    public function renderDataCell($model, $key, $index)
    {
        $cellContent = $this->renderDataCellContent($model, $key, $index);
        if ($cellContent != $this->grid->emptyCell) {
            $content = Html::button(Yii::t('mozayka', 'Actions') . ' <span class="caret"></span>', [
                'id' => 'actions-trigger',
                'class' => 'btn btn-default btn-xs',
                'data-dropdown2' => '#actions-dropdown2'
            ]) . Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown2-panel']), [
                'id' => 'actions-dropdown2',
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
