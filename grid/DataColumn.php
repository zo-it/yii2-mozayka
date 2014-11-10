<?php

namespace yii\mozayka\grid;

use yii\grid\DataColumn as YiiDataColumn,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup,
    yii\mozayka\web\DropdownAsset,
    Yii;


class DataColumn extends YiiDataColumn
{

    public function renderFilterCell()
    {
        $cellContent = $this->renderFilterCellContent();
        if ($cellContent != $this->grid->emptyCell) {
            $gridId = $this->grid->getId();
            $content = Html::button(Yii::t('mozayka', 'Filter') . ' <span class="caret"></span>', ['id' => 'filter-trigger-' . $this->attribute, 'class' => 'btn btn-default btn-sm', 'data-dropdown' => '#filter-dropdown-' . $this->attribute]);
            $cellContent .= Html::tag('div', ButtonGroup::widget([
                'buttons' => [
                    Html::button(Yii::t('mozayka', 'Apply'), ['class' => 'btn btn-primary btn-sm', 'onclick' => 'jQuery(\'#' . $gridId . ' #filter-trigger-' . $this->attribute . '\').dropdown2(\'hide\'); jQuery(\'#' . $gridId . '\').yiiGridView(\'applyFilter\');']),
                    Html::button(Yii::t('mozayka', 'Clear'), ['class' => 'btn btn-default btn-sm', 'onclick' => 'jQuery(\'#' . $gridId . ' #filter-dropdown-' . $this->attribute . '\').find(\'input[type="text"], input[type="hidden"], textarea\').val(\'\');'])
                ],
                'options' => ['class' => 'pull-right']
            ]), ['class' => 'clearfix']);
            $content .= Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown-panel']), ['id' => 'filter-dropdown-' . $this->attribute, 'class' => 'dropdown dropdown-tip']);
            if (!Yii::$app->getRequest()->getIsAjax()) {
                DropdownAsset::register($this->grid->getView());
            }
            return Html::tag('td', $content, $this->filterOptions);
        }
        return parent::renderFilterCell();
    }

    protected function renderFilterCellContent()
    {
        $form = $this->grid->form;
        $filterModel = $this->grid->filterModel;
        $filterFields = $this->grid->filterFields;
        if ($form && $filterModel && array_key_exists($this->attribute, $filterFields)) {
            $options = $filterFields[$this->attribute];
            return $form->field($filterModel, $this->attribute, $options);
        }
        return parent::renderFilterCellContent();
    }
}
