<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\data\ActiveDataProvider,
    yii\mozayka\helpers\ModelHelper,
    Yii;


class ListAction extends Action
{

    public $filterModelClass = null;

    public $searchScenario = Model::SCENARIO_DEFAULT;

    public $dataProviderConfig = [];

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = 'list';

    public function run()
    {
        $filterModel = null;
        $filterFields = [];
        $dataProvider = null;
        $request = Yii::$app->getRequest();
        if ($this->filterModelClass) {
            $filterModelClass = $this->filterModelClass;
            /** @var yii\db\ActiveRecordInterface $filterModel */
            $filterModel = new $filterModelClass(['scenario' => $this->searchScenario]);
            if ($request->getIsPost()) {
                $filterModel->load($request->getBodyParams());
            } else {
                $filterModel->load($request->getQueryParams());
            }
            // validation
            if ($request->getIsAjax() && $request->getQueryParam('validation')) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return ActiveForm::validate($filterModel);
            }
            // processing
            $filterFields = $this->prepareFields($filterModel);
            if ($filterModel->beforeSave(false)) {
                $dataProvider = $filterModel->search([$filterModel->formName() => []]);
                $filterModel->afterSave(false, []);
            }
        }
        $modelClass = $this->modelClass;
        if (!$dataProvider) {
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()]);
        }
        Yii::configure($dataProvider, $this->dataProviderConfig);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, null, ['query' => $dataProvider->query]);
        }
        $session = Yii::$app->getSession();
        $successMessage = $session->getFlash('success');
        $errorMessage = $session->getFlash('error');
        //
        $formConfig = array_merge($this->formConfig, [
            'validationUrl' => [$this->id, 'validation' => 1],
            'action' => ['list'], // no hidden fields
            'method' => 'get'
        ]);
        // rendering
        $viewParams = [
            'canCreate' => ModelHelper::canCreate($modelClass),
            'pluralHumanName' => ModelHelper::pluralHumanName($modelClass),
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'filterModel' => $filterModel,
            'filterFields' => $filterFields,
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'gridClass' => $this->gridClass,
            'gridConfig' => array_merge($this->gridConfig, [
                'dataProvider' => $dataProvider,
                'columns' => $this->prepareColumns($modelClass),
                'filterModel' => $filterModel,
                'filterFields' => $filterFields,
                'formClass' => $this->formClass,
                'formConfig' => $formConfig
            ])
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
