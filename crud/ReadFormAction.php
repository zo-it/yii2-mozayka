<?php

namespace yii\mozayka\crud;

use Yii;


class ReadFormAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = ['readOnly' => true];

    public $view = '@yii/mozayka/views/crud/read-form';

    public function run($id)
    {
        /* @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        // form config
        $formConfig = $this->formConfig;
        // rendering
        $viewParams = [
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->prepareFields($model)
        ];
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
