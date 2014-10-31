<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\helpers\StringHelper,
    yii\web\ForbiddenHttpException;


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

    public function canCreate($model = null, $params = [])
    {
        return true;
    }

    public function canRead($model = null, $params = [])
    {
        return true;
    }

    public function canUpdate($model = null, $params = [])
    {
        return true;
    }

    public function canDelete($model = null, $params = [])
    {
        return true;
    }

    public function canList($query = null, $params = [])
    {
        return true;
    }

    public function canChangePosition($model = null, $params = [])
    {
        return $this->canUpdate($model, $params);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        switch ($action) {
            case 'create-form': $allowed = $this->canCreate($model, $params); break;
            case 'read-form': $allowed = $this->canRead($model, $params); break;
            case 'update-form': $allowed = $this->canUpdate($model, $params); break;
            case 'delete-form': $allowed = $this->canDelete($model, $params); break;
            case 'list': $allowed = $this->canList($model, $params); break;
            case 'change-position': $allowed = $this->canChangePosition($model, $params); break;
            default: $allowed = false; break;
        }
        if (!$allowed) {
            throw new ForbiddenHttpException;
        }
    }
}
