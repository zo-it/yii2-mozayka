<?php

namespace yii\mozayka\crud;

//use yii\base\Action;


class DeleteAction extends Action
{

    public $view = '@yii/mozayka/views/active/delete';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
