<?php

namespace yii\mozayka\crud;


class CreateFormAction extends Action
{

    public $viewAction = 'updateForm';

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/create-form';

    public function run()
    {
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $this->formConfig,
            'view' => $this->view
        ]);
    }
}
