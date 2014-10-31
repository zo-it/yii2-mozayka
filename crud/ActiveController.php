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

    public function checkAccess($action, $model = null, $params = [])
    {
        $modelClass = $this->modelClass;
        switch ($action) {
            case 'create':
            case 'create-form':
                $allowed = $modelClass::canCreate($model, $params);
                break;
            case 'view':
            case 'read-form':
                $allowed = $modelClass::canRead($model, $params);
                break;
            case 'update':
            case 'update-form':
                $allowed = $modelClass::canUpdate($model, $params);
                break;
            case 'delete':
            case 'delete-form':
                $allowed = $modelClass::canDelete($model, $params);
                break;
            case 'index':
            case 'list':
                $query = null;
                if (array_key_exists('query', $params)) {
                    $query = $params['query'];
                    unset($params['query']);
                }
                $allowed = $modelClass::canList($query, $params);
                break;
            case 'change-position':
                $allowed = $modelClass::canChangePosition($model, $params);
                break;
            default:
                $allowed = false;
        }
        if (!$allowed) {
            throw new ForbiddenHttpException;
        }
    }
}
