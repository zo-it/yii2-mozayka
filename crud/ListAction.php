<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\data\ActiveDataProvider,
    yii\mozayka\db\ActiveRecord,
    Yii;


class ListAction extends Action
{

    public $filterModelClass = null;

    public $filterScenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = [];

    //public $dataProviderClass = 'yii\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/crud/list';

    public function run()
    {
        $modelClass = $this->modelClass;
        $dataProvider = null;
        $filterModel = null;
        $filterFields = [];
        $request = Yii::$app->getRequest();
        if ($this->filterModelClass) {
            /* @var yii\base\Model $filterModel */
            $filterModel = new $this->filterModelClass(['scenario' => $this->filterScenario]);
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
        // form config
        $formConfig = array_merge($this->formConfig, [
            'validationUrl' => [$this->id, 'validation' => 1],
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
        // can create?
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            $canCreate = $modelClass::canCreate();
        } else {
            $canCreate = method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate() : (bool)$modelClass::getTableSchema()->primaryKey;
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
            'filterModel' => $filterModel,
            'filterFields' => $filterFields,
            'gridClass' => $this->gridClass,
            'gridConfig' => $gridConfig,
            'canCreate' => $canCreate
        ];
        if ($request->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
