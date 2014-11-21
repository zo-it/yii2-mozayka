<?php

namespace yii\mozayka\crud;

use yii\mozayka\helpers\ModelHelper,
    Yii;


class ReadFormAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = 'read-form';

    public function run($id = null)
    {
        $modelClass = $this->modelClass;
        /** @var yii\db\ActiveRecordInterface $model */
        $model = $this->findModel($id);
        if (is_null($id)) {
            $id = ModelHelper::implodePrimaryKey($model);
        }
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        // rendering
        $viewParams = [
            'model' => $model,
            'listCaption' => $model->formName(),
            'id' => $id,
            'caption' => ModelHelper::caption($model),
            'fields' => $this->prepareFields($model),
            'formClass' => $this->formClass,
            'formConfig' => array_merge($this->formConfig, [
                'readOnly' => true
            ]),
            'canList' => ModelHelper::canList($modelClass)
        ];
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
