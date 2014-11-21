<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\base\Model,
    yii\helpers\StringHelper,
    yii\mozayka\helpers\ModelHelper,
    yii\web\ForbiddenHttpException,
    Yii;


class ActiveController extends YiiActiveController
{

    public $modelName = null;

    public $filterModelClass = null;

    public $deleteScenario = Model::SCENARIO_DEFAULT;

    public $searchScenario = Model::SCENARIO_DEFAULT;

    public function init()
    {
        if (!$this->modelName) {
            $this->modelName = StringHelper::basename(get_class($this), 'Controller');
        }
        if (!$this->modelClass) {
            $modelClass = 'app\models\\' . $this->modelName;
            if (class_exists($modelClass)) {
                $this->modelClass = $modelClass;
            } else {
                $modelClass = 'app\models\readonly\\' . $this->modelName;
                if (class_exists($modelClass)) {
                    $this->modelClass = $modelClass;
                }
            }
        }
        if (!$this->filterModelClass) {
            $filterModelClass = 'app\models\search\\' . $this->modelName . 'Search';
            if (class_exists($filterModelClass)) {
                $this->filterModelClass = $filterModelClass;
            } else {
                $filterModelClass = 'app\models\readonly\search\\' . $this->modelName . 'Search';
                if (class_exists($filterModelClass)) {
                    $this->filterModelClass = $filterModelClass;
                }
            }
        }
        parent::init();
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
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
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->deleteScenario
            ],
            'list' => [
                'class' => 'yii\mozayka\crud\ListAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'filterModelClass' => $this->filterModelClass,
                'filterScenario' => $this->searchScenario
            ],
            'change-position' => [
                'class' => 'yii\mozayka\crud\ChangePositionAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ]);
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
        $newModel = null;
        if (array_key_exists('newModel', $params)) {
            $newModel = $params['newModel'];
            unset($params['newModel']);
        }
        $query = null;
        if (array_key_exists('query', $params)) {
            $query = $params['query'];
            unset($params['query']);
        }
        switch ($action) {
            case 'create':
            case 'create-form': $allowed = ModelHelper::canCreate($this->modelClass, $params, $newModel); break;
            case 'view':
            case 'read-form': $allowed = ModelHelper::canRead($model); break;
            case 'update':
            case 'update-form': $allowed = ModelHelper::canUpdate($model); break;
            case 'delete':
            case 'delete-form': $allowed = ModelHelper::canDelete($model); break;
            case 'index':
            case 'list': $allowed = ModelHelper::canList($this->modelClass, $params, $query); break;
            case 'change-position': $allowed = ModelHelper::canChangePosition($model); break;
            default: $allowed = false;
        }
        if (!$allowed) {
            $user = Yii::$app->getUser();
            throw new ForbiddenHttpException(Yii::t('mozayka', 'Permission denied for user "{user}" to perform action "{action}".', [
                'user' => $user->getIsGuest() ? Yii::t('mozayka', 'Guest') : $user->getIdentity()->username,
                'action' => $this->id . '/' . $action
            ]));
        }
    }
}
