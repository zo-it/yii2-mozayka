<?php

namespace yii\mozayka\crud;

//use yii\base\Action;


class ListAction extends Action
{

    public $view = '@yii/mozayka/views/active/list';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
