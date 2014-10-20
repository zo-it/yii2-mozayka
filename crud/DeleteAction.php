<?php

namespace yii\mozayka\crud;


class DeleteAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $view = '@yii/mozayka/views/active/delete-form';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
