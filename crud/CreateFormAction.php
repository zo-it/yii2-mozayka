<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\helpers\VarDumper,
    Yii;


class CreateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $viewAction = 'update-form';

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/crud/create-form';

    public function run()
    {
        /* @var yii\db\ActiveRecord $model */
        $model = new $this->modelClass(['scenario' => $this->scenario]);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, /*$this->id*/'create', $model);
        }
        $session = Yii::$app->getSession();
        $successMessage = $session->getFlash('success');
        $errorMessage = $session->getFlash('error');
        $request = Yii::$app->getRequest();
        if ($request->getIsPost()) {
            $model->load($request->getBodyParams());
            // validation
            if ($request->getIsAjax() && $request->getQueryParam('validation')) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            // processing
            $saved = $model->validate() && $model->save();
            if ($saved) {
                $successMessage = Yii::t('mozayka', 'Record has been successfully saved.');
                $id = implode(',', array_values($model->getPrimaryKey(true)));
                if (!$request->getIsAjax()) {
                    $session->setFlash('success', $successMessage);
                    return $this->controller->redirect([$this->viewAction, 'id' => $id]);
                }
            } else {
                $errorMessage = Yii::t('mozayka', 'Record has not been saved.');
                Yii::error(VarDumper::dumpAsString([
                    'class' => get_class($model),
                    'attributes' => $model->getAttributes(),
                    'errors' => $model->getErrors()
                ]));
            }
            if ($request->getIsAjax()) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                if ($saved) {
                    return ['ok' => $saved, 'message' => $successMessage, 'id' => $id];
                } else {
                    return ['ok' => $saved, 'message' => $errorMessage];
                }
            }
        }
        // form config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = ['create-form', 'validation' => 1];
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->prepareFields($model)
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
