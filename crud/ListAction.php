<?php

namespace yii\mozayka\crud;

use Yii;


class ListAction extends Action
{

    public $dataProviderClass = 'yii\data\ActiveDataProvider';

    public $dataProviderConfig = [];

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $gridConfig = [];

    public $view = '@yii/mozayka/views/active/list';

    public function run()
    {
        $gridClass = $this->gridClass;
        if (!array_key_exists('dataProvider', $gridClass)) {
            $dataProviderConfig = $this->dataProviderConfig;
            if (!array_key_exists('query', $dataProviderConfig)) {
                $dataProviderConfig['query'] = call_user_func([$this->modelClass, 'find']);
            }
            $gridClass['dataProvider'] = Yii::createObject($this->dataProviderClass, $dataProviderConfig);
        }
        return $this->controller->render($this->view, [
            'gridClass' => $this->gridClass,
            'gridConfig' => $gridClass,
            'view' => $this->view
        ]);
    }
}
