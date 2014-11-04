<?php

namespace yii\mozayka\grid;

use yii\grid\DataColumn as YiiDataColumn,
    yii\helpers\Html,
    yii\mozayka\web\DropdownAsset,
    Yii;


class DataColumn extends YiiDataColumn
{

    public function renderFilterCell()
    {
        $cellContent = $this->renderFilterCellContent();
        if ($cellContent != $this->grid->emptyCell) {
            $content = Html::button(Yii::t('mozayka', 'Filter') . ' <span class="caret"></span>', ['class' => 'btn btn-default btn-sm', 'data-dropdown' => '#dropdown-' . $this->attribute]);
            $content .= Html::tag('div', Html::tag('div', $cellContent, ['class' => 'dropdown-panel']), ['id' => 'dropdown-' . $this->attribute, 'class' => 'dropdown dropdown-tip']);
            if (!Yii::$app->getRequest()->getIsAjax()) {
                DropdownAsset::register($this->grid->getView());
            }
            return Html::tag('td', $content, $this->filterOptions);
        }
        return parent::renderFilterCell();
    }
}
