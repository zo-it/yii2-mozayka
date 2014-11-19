<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\kladovka\helpers\Log,
    yii\mozayka\db\ActiveRecord,
    Yii;


class DeleteFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = '@yii/mozayka/views/crud/delete-form';

    public function run($id = null)
    {
        $modelClass = $this->modelClass;
        /* @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        if (is_null($id)) {
            $id = implode(',', array_values($model->getPrimaryKey(true)));
        }
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
                Log::modelErrors($model);
            }
            if ($request->getIsAjax()) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return [
                    'ok' => $deleted,
                    'message' => $deleted ? $successMessage : $errorMessage
                ];
            }
        }
        // form config
        $formConfig = array_merge($this->formConfig, [
            'validationUrl' => [$this->id, 'id' => $id, 'validation' => 1],
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
