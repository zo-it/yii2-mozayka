<?php

namespace yii\mozayka\controllers;

use yii\web\Controller;


class DefaultController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLoginForm()
    {
        return $this->render('login-form');
    }

    public function actionLogout()
    {
        return __METHOD__;
    }
}
