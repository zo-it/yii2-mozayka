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
        return $this->controller->render($this->view, [
            'gridClass' => $this->gridClass,
            'gridConfig' => [
                'dataProvider' => Yii::createObject($this->dataProviderClass, [
                    'query' => call_user_func([$this->modelClass, 'find'])
                ] + $this->dataProviderConfig)
            ] + $this->gridConfig,
            'view' => $this->view
        ]);
    }
}
