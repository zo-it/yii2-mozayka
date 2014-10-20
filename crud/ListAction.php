<?php

namespace yii\mozayka\crud;


class ListAction extends Action
{

    public $gridClass = 'yii\mozayka\grid\GridView';

    public $view = '@yii/mozayka/views/active/list';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
