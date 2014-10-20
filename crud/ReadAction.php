<?php

namespace yii\mozayka\crud;


class ReadAction extends Action
{

    public $view = '@yii/mozayka/views/active/read';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
