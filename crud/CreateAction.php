<?php

namespace yii\mozayka\crud;


class CreateAction extends Action
{

    public $viewAction = 'updateForm';

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $view = '@yii/mozayka/views/active/create-form';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
