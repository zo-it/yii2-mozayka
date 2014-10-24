<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\base\Model,
    yii\helpers\StringHelper;


class ActiveController extends YiiActiveController
{

    public function init()
    {
        if (!$this->modelClass) {
            $modelClass = 'app\models\\' . StringHelper::basename(get_class($this), 'Controller');
            if (class_exists($modelClass)) {
                $this->modelClass = $modelClass;
            }
        }
        parent::init();
    }

    public function actions()
    {
        return parent::actions() + [
            'create-form' => [
                'class' => 'yii\mozayka\crud\CreateFormAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario
            ],
            'read-form' => [
                'class' => 'yii\mozayka\crud\ReadFormAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'update-form' => [
                'class' => 'yii\mozayka\crud\UpdateFormAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario
            ],
            'delete-form' => [
                'class' => 'yii\mozayka\crud\DeleteFormAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'list' => [
                'class' => 'yii\mozayka\crud\ListAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'change-position' => [
                'class' => 'yii\mozayka\crud\ChangePositionAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ];
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (array_key_exists('contentNegotiator', $behaviors) && ($this->action instanceof Action)) { // yii\mozayka\crud\Action
            unset($behaviors['contentNegotiator']);
        }
        return $behaviors;
    }
}
