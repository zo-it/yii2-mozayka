<?php

namespace yii\mozayka\grid;

use yii\grid\DataColumn as YiiDataColumn,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup,
    yii\mozayka\web\DropdownAsset,
    Yii;


class DataColumn extends YiiDataColumn
{

    protected function renderFilterCellContent()
    {
        $form = $this->grid->getForm();
        $filterModel = $this->grid->filterModel;
        $filterFields = $this->grid->filterFields;
        if ($form && $filterModel && array_key_exists($this->attribute, $filterFields)) {
            $gridId = $this->grid->getId();
            return $form->field($filterModel, $this->attribute, $filterFields[$this->attribute]) . Html::tag('div', ButtonGroup::widget([
                'buttons' => [
                    Html::button('<span class="glyphicon glyphicon-search"></span> ' . Yii::t('mozayka', 'Search'), [
                        'class' => 'btn btn-primary btn-sm',
                        'onclick' => 'jQuery(\'#' . $gridId . ' #filter-trigger-' . $this->attribute . '\').dropdown2(\'hide\'); jQuery(\'#' . $gridId . '\').yiiGridView(\'applyFilter\');'
                    ]),
                    Html::button('<span class="glyphicon glyphicon-ban-circle"></span> ' . Yii::t('mozayka', 'Clear'), [
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => 'jQuery(\'#' . $gridId . ' #filter-dropdown2-' . $this->attribute . '\').find(\'input[type="text"], input[type="hidden"], textarea, select\').val(\'\');'
                    ])
                ],
                'options' => ['class' => 'pull-right']
            ]), ['class' => 'clearfix']);
        }
        return parent::renderFilterCellContent();
    }

    public function renderFilterCell()
    {
        $cellContent = $this->renderFilterCellContent();
        if ($cellContent && ($cellContent != $this->grid->emptyCell)) {
            $content = Html::button('<span class="glyphicon glyphicon-filter"></span>', [
                'title' => Yii::t('mozayka', 'Filter'),
                'id' => 'filter-trigger-' . $this->attribute,
                'class' => 'btn btn-default btn-xs',
                'data-dropdown2' => '#filter-dropdown2-' . $this->attribute
            ]) . Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown2-panel']), [
                'id' => 'filter-dropdown2-' . $this->attribute,
                'class' => 'dropdown2 dropdown2-tip' . ((array_search($this, $this->grid->columns) + 1 > count($this->grid->columns) / 2) ? ' dropdown2-anchor-right' : '')
            ]);
            if (!Yii::$app->getRequest()->getIsAjax()) {
                DropdownAsset::register($this->grid->getView());
            }
            return Html::tag('td', $content, $this->filterOptions);
        }
        return parent::renderFilterCell();
    }
}
