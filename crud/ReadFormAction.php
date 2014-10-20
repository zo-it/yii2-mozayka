<?php

namespace yii\mozayka\crud;


class ReadFormAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/read-form';

    public function run()
    {
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $this->formConfig
        ]);
    }
}
