<?php

namespace yii\mozayka\crud;

use yii\base\Model,
    yii\web\Response,
    yii\mozayka\form\ActiveForm,
    yii\mozayka\db\ActiveRecord,
    Yii;


class ListAction extends Action
{

    public $filterModelClass = null;

    public $filterScenario = Model::SCENARIO_DEFAULT;

    public $formClass = 'yii\mozayka\form\ActiveForm';

    public $formConfig = ['method' => 'get'];

    //public $dataProviderClass = 'yii\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/crud/list';

    public function run()
    {
        $request = Yii::$app->getRequest();
        if ($this->filterModelClass) {
            /* @var yii\base\Model $filterModel */
            $filterModel = new $this->filterModelClass(['scenario' => $this->filterScenario]);
if ($request->getIsGet()) {
$filterModel->load($request->getQueryParams());
}
// validation
if ($request->getIsAjax() && $request->getQueryParam('validation')) {
Yii::$app->getResponse()->format = Response::FORMAT_JSON;
return ActiveForm::validate($filterModel);
}
// processing
$dataProvider = $filterModel->search([]);
            $gridConfig['filterModel'] = $filterModel;
            $gridConfig['filterFields'] = $this->prepareFields($filterModel);
        }

$modelClass = $this->modelClass;
$dataProvider = new ActiveDataProvider(['query' => $modelClass::find()]);

        $gridConfig = $this->gridConfig;
        if (!array_key_exists('dataProvider', $gridConfig)) {
            $dataProviderConfig = $this->dataProviderConfig;
            if (!array_key_exists('query', $dataProviderConfig)) {
                $modelClass = $this->modelClass;
                $dataProviderConfig['query'] = $modelClass::find();
            }
            $gridConfig['dataProvider'] = new $this->dataProviderClass($dataProviderConfig);
        }
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, null, ['query' => $gridConfig['dataProvider']->query]);
        }
        $session = Yii::$app->getSession();
        $successMessage = $session->getFlash('success');
        $errorMessage = $session->getFlash('error');
        // form config
        $formConfig = $this->formConfig;
        if (!array_key_exists('validationUrl', $formConfig)) {
            $formConfig['validationUrl'] = [$this->id, 'validation' => 1];
        }
        // grid config
        if (!array_key_exists('columns', $gridConfig)) {
            $columns = [];
            //$columns[] = ['class' => 'yii\grid\CheckboxColumn'];
            $columns = array_merge($columns, $this->prepareColumns(new $this->modelClass));
            $columns[] = ['class' => 'yii\mozayka\grid\ActionColumn'];
            $gridConfig['columns'] = $columns;
        }

        // can create?
        $modelClass = $this->modelClass;
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            $canCreate = $modelClass::canCreate();
        } else {
            $canCreate = method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate() : true;
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'formClass' => $this->formClass,
            'formConfig' => $formConfig,
'filterModel' => $filterModel,
'filterFields' => $filterModel ? $this->prepareFields($filterModel) : [],
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
