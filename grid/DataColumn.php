<?php

namespace yii\mozayka\grid;

use yii\grid\DataColumn as YiiDataColumn,
    yii\mozayka\web\DropdownAsset,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup,
    Yii;


class DataColumn extends YiiDataColumn
{

    public $sortLinkOptions = ['class' => 'gv-sort-link'];

    public function init()
    {
        if (!Yii::$app->getRequest()->getIsAjax()) {
            DropdownAsset::register($this->grid->getView());
        }
        parent::init();
    }

    protected function renderFilterCellContent()
    {
        $gridId = $this->grid->getId();
        $dropdownId = $gridId . '-dropdown2-filter-' . $this->attribute;
        $form = $this->grid->getForm();
        $filterModel = $this->grid->filterModel;
        $filterFields = $this->grid->filterFields;
        if ($form && $filterModel && array_key_exists($this->attribute, $filterFields)) {
            $cellContent = $form->field($filterModel, $this->attribute, $filterFields[$this->attribute]) . Html::tag('div', ButtonGroup::widget([
                'buttons' => [
                    Html::button('<span class="glyphicon glyphicon-search"></span> ' . Yii::t('mozayka', 'Apply'), [
                        'class' => 'btn btn-primary btn-sm',
                        'onclick' => 'jQuery(document).dropdown2(\'hide\'); jQuery(\'#' . $gridId . '\').yiiGridView(\'applyFilter\');'
                    ]),
                    Html::button('<span class="glyphicon glyphicon-ban-circle"></span> ' . Yii::t('mozayka', 'Reset'), [
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => 'jQuery(\'#' . $dropdownId . '\').find(\'input[type="text"], input[type="hidden"], textarea, select\').val(\'\');'
                    ])
                ],
                'options' => ['class' => 'pull-right']
            ]), ['class' => 'clearfix']);
        } else {
            $cellContent = parent::renderFilterCellContent();
        }
        // dropdown2-panel
        if ($cellContent && ($cellContent != $this->grid->emptyCell)) {
            $cellContent = Html::button('<span class="glyphicon glyphicon-filter"></span>', [
                'title' => Yii::t('mozayka', 'Filter'),
                'class' => 'btn btn-default btn-xs',
                'data-dropdown2' => '#' . $dropdownId
            ]) . Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown2-panel']), [
                'id' => $dropdownId,
                'class' => 'dropdown2 dropdown2-tip' . ((array_search($this, $this->grid->columns) + 1 > count($this->grid->columns) / 2) ? ' dropdown2-anchor-right' : '')
            ]);
        }
        return $cellContent;
    }
}
