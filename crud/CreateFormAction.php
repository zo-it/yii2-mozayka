<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\mozayka\helpers\ModelHelper,
    Yii;


class CreateFormAction extends Action
{

    public $scenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $view = 'create-form';

    public function run()
    {
        $id = null;
        $modelClass = $this->modelClass;
        /** @var yii\db\ActiveRecordInterface $model */
        $model = new $modelClass(['scenario' => $this->scenario]);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, null, ['newModel' => $model]);
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
                $id = ModelHelper::implodePrimaryKey($model);
                $successMessage = Yii::t('mozayka', 'Record "{caption}" has been successfully saved.', ['caption' => ModelHelper::caption($model)]);
                if (!$request->getIsAjax()) {
                    $session->setFlash('success', $successMessage);
                    if (ModelHelper::canUpdate($model)) {
                        $url = ['update-form', 'id' => $id];
                    } elseif (ModelHelper::canRead($model)) {
                        $url = ['read-form', 'id' => $id];
                    } elseif (ModelHelper::canList($modelClass)) {
                        $url = ['list'];
                    } else {
                        $url = Yii::$app->getHomeUrl();
                    }
                    return $this->controller->redirect($url);
                }
            } else {
                ModelHelper::log($model);
                $errorMessage = Yii::t('mozayka', 'Record "{caption}" has not been saved.', ['caption' => ModelHelper::caption($model)]);
            }
            if ($request->getIsAjax()) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return [
                    'ok' => $saved,
                    'message' => $saved ? $successMessage : $errorMessage,
                    'id' => $saved ? $id : false
                ];
            }
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'model' => $model,
            'fields' => $this->prepareFields($model),
            'formClass' => $this->formClass,
            'formConfig' => array_merge($this->formConfig, [
                'validationUrl' => [$this->id, 'validation' => 1]
            ]),
            'canList' => ModelHelper::canList($modelClass),
            'listCaption' => ModelHelper::listCaption($modelClass)
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
