<?php

namespace yii\mozayka\crud;

use yii\mozayka\db\ActiveRecord,
    Yii;


class ListAction extends Action
{

    public $filterModelClass = null;

    public $dataProviderClass = 'yii\mozayka\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/crud/list';

    public function run()
    {
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
        // grid config
        if (!array_key_exists('columns', $gridConfig)) {
            $columns = [];
            //$columns[] = ['class' => 'yii\grid\CheckboxColumn'];
            $columns = array_merge($columns, $this->prepareColumns(new $this->modelClass));
            $columns[] = ['class' => 'yii\mozayka\grid\ActionColumn'];
            $gridConfig['columns'] = $columns;
        }
        if (!array_key_exists('filterModel', $gridConfig) && $this->filterModelClass) {
            $gridConfig['filterModel'] = new $this->filterModelClass;
        }
        // can create
        $modelClass = $this->modelClass;
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            $canCreate = $modelClass::canCreate();
        } else {
            $canCreate = is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate() : true;
        }
        // rendering
        $viewParams = [
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'gridClass' => $this->gridClass,
            'gridConfig' => $gridConfig,
            'canCreate' => $canCreate
        ];
        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->controller->renderPartial($this->view, $viewParams);
        } else {
            return $this->controller->render($this->view, $viewParams);
        }
    }
}
