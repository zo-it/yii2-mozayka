<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\helpers\VarDumper,
    yii\mozayka\db\ActiveRecord,
    Yii;


class DeleteFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = ['readOnly' => true];

    public $view = '@yii/mozayka/views/crud/delete-form';

    public function run($id = null)
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
            $deleted = $model->validate() && $model->delete();
            if ($deleted) {
                $successMessage = Yii::t('mozayka', 'Record has been successfully deleted.');
                if (!$request->getIsAjax()) {
                    $session->setFlash('success', $successMessage);
                    return $this->controller->redirect(['list']);
                }
            } else {
                $errorMessage = Yii::t('mozayka', 'Record has not been deleted.');
                Yii::error(VarDumper::dumpAsString([
                    'class' => get_class($model),
                    'attributes' => $model->getAttributes(),
                    'errors' => $model->getErrors()
                ]));
            }
            if ($request->getIsAjax()) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                if ($deleted) {
                    return ['ok' => $deleted, 'message' => $successMessage];
                } else {
                    return ['ok' => $deleted, 'message' => $errorMessage];
                }
            }
        }
        // form config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = [$this->id, 'id' => $id, 'validation' => 1];
        }
        // can list?
        $modelClass = $this->modelClass;
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            $canList = $modelClass::canList();
        } else {
            $canList = method_exists($modelClass, 'canList') && is_callable([$modelClass, 'canList']) ? $modelClass::canList() : true;
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'model' => $model,
            'fields' => $this->prepareFields($model),
            'canList' => $canList
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
