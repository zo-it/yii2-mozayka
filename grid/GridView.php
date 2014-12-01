<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView,
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
            $view->beginBlock('__ITEMS__');
            $formClass = $this->formClass;
            $this->_form = $formClass::begin($this->formConfig);
            echo parent::renderItems();
            $formClass::end();
            $view->endBlock();
            return $view->blocks['__ITEMS__'];
        }
        return parent::renderItems();
    }

    public function getForm()
    {
        return $this->_form;
    }
}
