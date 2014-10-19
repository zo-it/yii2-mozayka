<?php

namespace yii\mozayka\crud;

//use yii\base\Action;


class CreateAction extends Action
{

    public $view = '@yii/mozayka/views/active/create';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
