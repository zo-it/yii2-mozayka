<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView;


class GridView extends YiiGridView
{

    public $dataColumnClass = 'yii\mozayka\grid\DataColumn';

    public $filterFields = [];

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = ['method' => 'get'];

    private $_form = null;

    public function init()
    {
        $this->setId(uniqid($this->getId()));
        parent::init();
    }

    public function getForm()
    {
        return $this->_form;
    }

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
}
