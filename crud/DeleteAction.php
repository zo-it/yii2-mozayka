<?php

namespace yii\mozayka\crud;


class DeleteAction extends Action
{

    public $view = '@yii/mozayka/views/active/delete';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
