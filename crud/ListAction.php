<?php

namespace yii\mozayka\crud;

use Yii;


class ListAction extends Action
{

    public $dataProviderClass = 'yii\mozayka\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/active/list';

    public function run()
    {
        $gridConfig = $this->gridConfig;
        if (!array_key_exists('dataProvider', $gridConfig)) {
            $dataProviderConfig = $this->dataProviderConfig;
            if (!array_key_exists('query', $dataProviderConfig)) {
                $modelClass = $this->modelClass;
                $dataProviderConfig['query'] = $modelClass::find();
            }
            $gridConfig['dataProvider'] = Yii::createObject($this->dataProviderClass, $dataProviderConfig);
        }
        return $this->controller->render($this->view, [
            'gridClass' => $this->gridClass,
            'gridConfig' => $gridConfig
        ]);
    }
}
