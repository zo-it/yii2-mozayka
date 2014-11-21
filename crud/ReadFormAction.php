<?php

namespace yii\mozayka\crud;

use yii\mozayka\db\ActiveRecord,
    Yii;


class ReadFormAction extends Action
{

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = 'read-form';

    public function run($id = null)
    {
        $modelClass = $this->modelClass;
        /** @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        if (is_null($id)) {
            $id = implode(',', array_values($model->getPrimaryKey(true)));
        }
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        // form config
        $formConfig = array_merge($this->formConfig, [
            'readOnly' => true
        ]);
        // can list?
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            $canList = $modelClass::canList();
        } else {
            $canList = method_exists($modelClass, 'canList') && is_callable([$modelClass, 'canList']) ? $modelClass::canList() : true;
        }
        // rendering
        $viewParams = [
            'model' => $model,
            'listCaption' => $model->formName(),
            'id' => $id,
            'caption' => '#' . $id,
            'fields' => $this->prepareFields($model),
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'canList' => $canList
        ];
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
