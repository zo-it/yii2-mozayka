<?php

namespace yii\mozayka\crud;

use yii\base\Model;


class UpdateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/update-form';

    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->setScenario($this->scenario);
        $formConfig = $this->formConfig;
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->buildFields($model)
        ]);
    }
}
