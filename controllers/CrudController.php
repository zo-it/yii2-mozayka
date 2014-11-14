<?php

namespace yii\mozayka\controllers;

use yii\mozayka\crud\ActiveController,
    yii\helpers\Inflector,
    Yii;


class CrudController extends ActiveController
{

    public function init()
    {
        $modelId = Yii::$app->getRequest()->getQueryParam('modelId');
        if ($modelId) {
            $this->modelName = Inflector::id2camel($modelId);
        }
        parent::init();
    }
}
