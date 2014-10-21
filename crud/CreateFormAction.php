<?php

namespace yii\mozayka\crud;

use yii\base\Model;


class CreateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $viewAction = 'updateForm';

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/create-form';

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        $model = new $this->modelClass(['scenario' => $this->scenario]);
        $formConfig = $this->formConfig;
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->buildFields($model)
        ]);
    }
}
