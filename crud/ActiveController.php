<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\helpers\StringHelper,
    yii\web\ForbiddenHttpException;


class ActiveController extends YiiActiveController
{

    public $basename = null;

    public $filterModelClass = null;

    public function init()
    {
        $this->basename = StringHelper::basename(get_class($this), 'Controller');
        if (!$this->modelClass) {
            $modelClass = 'app\models\\' . $this->basename;
            if (class_exists($modelClass)) {
                $this->modelClass = $modelClass;
            }
        }
        if (!$this->filterModelClass) {
            $filterModelClass = 'app\models\filters\\' . $this->basename;
            if (class_exists($filterModelClass)) {
                $this->filterModelClass = $filterModelClass;
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
                'filterModelClass' => $this->filterModelClass,
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
        switch ($action) {
            case 'create':
            case 'create-form':
                $newModel = null;
                if (array_key_exists('newModel', $params)) {
                    $newModel = $params['newModel'];
                    unset($params['newModel']);
                }
                $modelClass = $this->modelClass;
                $allowed = $modelClass::canCreate($params, $newModel);
                break;
            case 'view':
            case 'read-form':
                $allowed = $model->canRead($params);
                break;
            case 'update':
            case 'update-form':
                $allowed = $model->canUpdate($params);
                break;
            case 'delete':
            case 'delete-form':
                $allowed = $model->canDelete($params);
                break;
            case 'index':
            case 'list':
                $query = null;
                if (array_key_exists('query', $params)) {
                    $query = $params['query'];
                    unset($params['query']);
                }
                $modelClass = $this->modelClass;
                $allowed = $modelClass::canList($params, $query);
                break;
            case 'change-position':
                $allowed = $model->canChangePosition($params);
                break;
            default:
                $allowed = false;
        }
        if (!$allowed) {
            throw new ForbiddenHttpException;
        }
    }
}
