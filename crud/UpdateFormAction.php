<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\helpers\VarDumper,
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
        $model->setScenario($this->scenario);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
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
            if ($model->validate() && $model->save()) {
                $successMessage = Yii::t('mozayka', 'Data has been successfully saved.');
                if (!$request->getIsAjax()) {
                    $session->setFlash('success', $successMessage);
                    return $this->controller->redirect(['update-form', 'id' => $id]);
                }
            } else {
                $errorMessage = Yii::t('mozayka', 'Data has not been saved.');
                Yii::error(VarDumper::dumpAsString([
                    'class' => get_class($model),
                    'attributes' => $model->getAttributes(),
                    'errors' => $model->getErrors()
                ]));
            }
        }
        // form config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = ['update-form', 'id' => $id, 'validation' => 1];
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
            if (array_key_exists('application/json', $request->getAcceptableContentTypes())) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                $viewParams['successMessage'] = null;
                $viewParams['errorMessage'] = null;
                return [
                    'successMessage' => $successMessage,
                    'errorMessage' => $errorMessage,
                    'html' => $this->controller->renderPartial($this->view, $viewParams)
                ];
            } else {
                return $this->controller->renderPartial($this->view, $viewParams);
            }
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
