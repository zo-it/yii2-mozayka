<?php

namespace yii\mozayka\crud;


class ReadAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $view = '@yii/mozayka/views/active/read-form';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
