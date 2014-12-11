<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\mozayka\helpers\ModelHelper,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    Yii;


class DeleteFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = 'delete-form';

    public function run($id = null)
    {
        $modelClass = $this->modelClass;
        /** @var yii\db\BaseActiveRecord $model */
        $model = $this->findModel($id);
        if (is_null($id)) {
            $id = ModelHelper::getPrimaryKey($model);
        }
        $model->setScenario($this->scenario);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model, ['id' => $id]);
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
                $successMessage = Yii::t('mozayka', 'Record "{record}" has been successfully deleted.', ['record' => ModelHelper::getDisplayField($model)]);
                if (!$request->getIsAjax()) {
                    $session->setFlash('success', $successMessage);
                    if (ModelHelper::canList($modelClass)) {
                        $url = ['list'];
                    } else {
                        $url = Yii::$app->getHomeUrl();
                    }
                    return $this->controller->redirect($url);
                }
            } else {
                ModelHelper::log($model);
                $errorMessage = Yii::t('mozayka', 'Record "{record}" has not been deleted.', ['record' => ModelHelper::getDisplayField($model)]);
            }
            if ($request->getIsAjax()) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return [
                    'ok' => $deleted,
                    'message' => $deleted ? $successMessage : $errorMessage
                ];
            }
        }
        // rendering
        $viewParams = [
            'canList' => ModelHelper::canList($modelClass),
            'pluralHumanName' => ModelHelper::pluralHumanName($modelClass),
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'model' => $model,
            'id' => $id,
            'displayField' => ModelHelper::getDisplayField($model),
            'fields' => $this->prepareFields($model),
            'formClass' => $this->formClass,
            'formConfig' => array_merge($this->formConfig, [
                'validationUrl' => [$this->id, 'id' => $id, 'validation' => 1],
                'readOnly' => true
            ])
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
