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
            $options = $filterFields[$this->attribute];
            return $form->field($filterModel, $this->attribute, $options);
        }
        return parent::renderFilterCellContent();
    }

    public function renderFilterCell()
    {
        $cellContent = $this->renderFilterCellContent();
        if ($cellContent != $this->grid->emptyCell) {
            $gridId = $this->grid->getId();
            $cellContent .= Html::tag('div', ButtonGroup::widget([
                'buttons' => [
                    Html::button(Yii::t('mozayka', 'Apply'), [
                        'class' => 'btn btn-primary btn-sm',
                        'onclick' => 'jQuery(\'#' . $gridId . ' #filter-trigger-' . $this->attribute . '\').dropdown2(\'hide\'); jQuery(\'#' . $gridId . '\').yiiGridView(\'applyFilter\');'
                    ]),
                    Html::button(Yii::t('mozayka', 'Clear'), [
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => 'jQuery(\'#' . $gridId . ' #filter-dropdown2-' . $this->attribute . '\').find(\'input[type="text"], input[type="hidden"], textarea, select\').val(\'\');'
                    ])
                ],
                'options' => ['class' => 'pull-right']
            ]), ['class' => 'clearfix']);
            $content = Html::button(Yii::t('mozayka', 'Filter') . ' <span class="caret"></span>', [
                'id' => 'filter-trigger-' . $this->attribute,
                'class' => 'btn btn-default btn-sm',
                'data-dropdown2' => '#filter-dropdown2-' . $this->attribute
            ]) . Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown2-panel']), [
                'id' => 'filter-dropdown2-' . $this->attribute,
                'class' => 'dropdown2 dropdown2-tip'
            ]);
            if (!Yii::$app->getRequest()->getIsAjax()) {
                DropdownAsset::register($this->grid->getView());
            }
            return Html::tag('td', $content, $this->filterOptions);
        }
        return parent::renderFilterCell();
    }
}
