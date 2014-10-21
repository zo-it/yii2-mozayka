<?php

namespace yii\mozayka\crud;


class ListAction extends Action
{

    public $dataProviderClass = 'yii\mozayka\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/active/list';

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        $gridConfig = $this->gridConfig;
        if (!array_key_exists('dataProvider', $gridConfig)) {
            $dataProviderConfig = $this->dataProviderConfig;
            if (!array_key_exists('query', $dataProviderConfig)) {
                $modelClass = $this->modelClass;
                $dataProviderConfig['query'] = $modelClass::find();
            }
            $dataProviderClass = $this->dataProviderClass;
            $gridConfig['dataProvider'] = new $dataProviderClass($dataProviderConfig);
        }
        return $this->controller->render($this->view, [
            'gridClass' => $this->gridClass,
            'gridConfig' => $gridConfig
        ]);
    }
}
