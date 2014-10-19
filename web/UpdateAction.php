<?php

namespace yii\mozayka\web;

use yii\base\Action;


class UpdateAction extends Action
{

    public $view = '@yii/mozayka/views/active/update';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
