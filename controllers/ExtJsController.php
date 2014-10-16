<?php

namespace yii\mozayka\controllers;

use yii\web\Controller;


class ExtJsController extends Controller
{

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }

    public function actionApplicationJs()
    {
        return __METHOD__;
    }
}
