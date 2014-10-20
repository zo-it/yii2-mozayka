<?php

namespace yii\mozayka\crud;


class UpdateAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $view = '@yii/mozayka/views/active/update-form';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
