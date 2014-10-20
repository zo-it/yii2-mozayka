<?php

namespace yii\mozayka\web;

use yii\rest\ActiveController as YiiActiveController;


class ActiveController extends YiiActiveController
{

    public function init()
    {
        parent::init();
    }

    public function actions()
    {
        return parent::actions() + [
            'list' => [
                'class' => 'yii\mozayka\crud\ListAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'createForm' => [
                'class' => 'yii\mozayka\crud\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario
            ],
            'readForm' => [
                'class' => 'yii\mozayka\crud\ReadAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'updateForm' => [
                'class' => 'yii\mozayka\crud\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario
            ],
            'changePosition' => [
                'class' => 'yii\mozayka\crud\ChangePositionAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'deleteForm' => [
                'class' => 'yii\mozayka\crud\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ];
    }
}
