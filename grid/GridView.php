<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView;


class GridView extends YiiGridView
{

    public $dataColumnClass = 'yii\mozayka\grid\DataColumn';

    public $filterFields = [];

    public $filterRowOptions = ['class' => 'filters hidden-print'];

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public function init()
    {
        $this->setId(uniqid($this->getId()));
        parent::init();
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
}
