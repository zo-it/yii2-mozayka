<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView,
    yii\mozayka\helpers\ModelHelper,
    yii\helpers\Html,
    Yii;


class GridView extends YiiGridView
{

    public $dataColumnClass = 'yii\mozayka\grid\DataColumn';

    public $tableOptions = ['class' => 'table table-striped table-bordered table-condensed'];

    public $filterRowOptions = ['class' => 'filters hidden-print'];

    public $filterFields = [];

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public function init()
    {
        $this->setId(uniqid($this->getId()));
        parent::init();
    }

    public function renderSummary()
    {
        if (is_null($this->summary) && extension_loaded('intl') && $this->dataProvider->getCount()) {
            $pagination = $this->dataProvider->getPagination();
            if ($pagination && ($pagination->getPageCount() > 1)) {
                $this->summary = Yii::t('mozayka', 'Records <b>{begin, number}-{end, number}</b> (total <b>{totalCount, number}</b> {totalCount, plural, one{record} other{records}}).') . ' ' . Yii::t('mozayka', 'Page <b>{page, number}</b> (total <b>{pageCount, number}</b> {pageCount, plural, one{page} other{pages}}).');
            } else {
                $this->summary = Yii::t('mozayka', 'Total <b>{totalCount, number}</b> {totalCount, plural, one{record} other{records}}.');
            }
        }
        return parent::renderSummary();
    }

    private $_form = null;

    public function renderItems()
    {
        if ($this->filterModel && $this->filterFields) {
            $view = $this->getView();
            $view->beginBlock('grid-items');
            $formClass = $this->formClass;
            $this->_form = $formClass::begin($this->formConfig);
            echo parent::renderItems();
            $formClass::end();
            $view->endBlock();
            return $view->blocks['grid-items'];
        }
        return parent::renderItems();
    }

    public function getForm()
    {
        return $this->_form;
    }

    public function renderTableRow($model, $key, $index)
    {
        $savedRowOptions = $this->rowOptions;
        if (is_callable($this->rowOptions)) {
            $callableRowOptions = $this->rowOptions;
            $this->rowOptions = $callableRowOptions($model, $key, $index, $this);
        }
        $rowOptions = ModelHelper::rowOptions($model);
        if ($rowOptions) {
            $this->rowOptions = array_merge($this->rowOptions, $rowOptions);
        }
        $rowCssClass = ModelHelper::rowCssClass($model);
        if ($rowCssClass) {
            Html::addCssClass($this->rowOptions, $rowCssClass);
        }
        $rowCssStyle = ModelHelper::rowCssStyle($model);
        if ($rowCssStyle) {
            Html::addCssStyle($this->rowOptions, $rowCssStyle);
        }
        $tableRow = parent::renderTableRow($model, $key, $index);
        $this->rowOptions = $savedRowOptions;
        return $tableRow;
    }
}
