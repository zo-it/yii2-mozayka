<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\web\ServerErrorHttpException,
    Yii;


class DeleteFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/delete-form';

    public function run($id)
    {
        /* @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        $model->setScenario($this->scenario);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
$request = Yii::$app->getRequest();
if ($request->getIsPost()) {
    if ($request->getIsAjax()) {
        if ($request->getQueryParam('validation')) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        throw new ServerErrorHttpException;
    } elseif ($model->validate() && $model->delete()) {
        return $this->controller->redirect(['list']);
    }
}
        // config
        $formConfig = $this->formConfig;
        $formConfig['readOnly'] = true;
        // render
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->prepareFields($model)
        ]);
    }
}
