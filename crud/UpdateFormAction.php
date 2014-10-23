<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\web\ServerErrorHttpException,
    Yii;


class UpdateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/active/update-form';

    public function run($id)
    {
        /* @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
$model->setScenario($this->scenario);
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
        return $this->controller->redirect(['update-form', 'id' => $id]);
    }
}
        // config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = ['update-form', 'id' => $id, 'validation' => 1];
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
