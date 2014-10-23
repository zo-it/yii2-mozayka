<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\web\ServerErrorHttpException,
    Yii;


class CreateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $viewAction = 'update-form';

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/create-form';

    public function run()
    {
        /* @var yii\db\ActiveRecord $model */
        $model = new $this->modelClass(['scenario' => $this->scenario]);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
$request = Yii::$app->getRequest();
if ($request->getIsPost()) {
    $model->load($request->getBodyParams());
    if ($request->getIsAjax()) {
        if ($request->getQueryParam('validation')) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        throw new ServerErrorHttpException;
    } elseif ($model->validate() && $model->save()) {
        return $this->controller->redirect([$this->viewAction, 'id' => implode(',', $model->getPrimaryKey(true))]);
    }
}
        // config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = ['create-form', 'validation' => 1];
        }
        // render
        return $this->controller->render($this->view, [
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->prepareFields($model)
        ]);
    }
}
