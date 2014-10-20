<?php

namespace yii\mozayka\crud;


class CreateAction extends Action
{

    public $viewAction = 'updateForm';

    public $view = '@yii/mozayka/views/active/create';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
