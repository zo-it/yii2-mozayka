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

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    //public $dataProviderClass = 'yii\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = 'list';

    public function run()
    {
        $modelClass = $this->modelClass;
        $dataProvider = null;
        $filterModel = null;
        $filterFields = [];
        $request = Yii::$app->getRequest();
        if ($this->filterModelClass) {
            /** @var yii\db\ActiveRecordInterface $filterModel */
            $filterModel = new $this->filterModelClass(['scenario' => $this->searchScenario]);
            $filterModel->load($request->getIsPost() ? $request->getBodyParams() : $request->getQueryParams());
            // validation
            if ($request->getIsAjax() && $request->getQueryParam('validation')) {
                Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                return ActiveForm::validate($filterModel);
            }
            // processing
if ($filterModel->beforeSave(false)) {
$dataProvider = $filterModel->search([$filterModel->formName() => []]);
$filterModel->afterSave(false, []);
}
        }
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
        // grid config
        $gridConfig = $this->gridConfig;
        $gridConfig['dataProvider'] = $dataProvider;
        if ($filterModel) {
            $filterFields = $this->prepareFields($filterModel);
$gridConfig = array_merge($gridConfig, [
'formClass' => $this->formClass,
'formConfig' => $formConfig,
'filterModel' => $filterModel,
'filterFields' => $filterFields
]);
        }
        if (!array_key_exists('columns', $gridConfig)) {
            $columns = [];
            //$columns[] = ['class' => 'yii\grid\CheckboxColumn'];
            $columns = array_merge($columns, $this->prepareColumns(new $modelClass));
            $columns[] = ['class' => 'yii\mozayka\grid\ActionColumn'];
            $gridConfig['columns'] = $columns;
        }
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
            'gridConfig' => $gridConfig
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
