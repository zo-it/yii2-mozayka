<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\helpers\StringHelper,
    yii\mozayka\db\ActiveRecord,
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
            $filterModelClass = 'app\models\filters\\' . $this->basename . 'Filter';
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
                if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
                    $allowed = $modelClass::canCreate($params, $newModel);
                } else {
                    $allowed = is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate($params, $query) : true;
                }
                break;
            case 'view':
            case 'read-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canRead($params);
                } else {
                    $allowed = is_callable([$model, 'canRead']) ? $model->canRead() : true;
                }
                break;
            case 'update':
            case 'update-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canUpdate($params);
                } else {
                    $allowed = is_callable([$model, 'canUpdate']) ? $model->canUpdate() : true;
                }
                break;
            case 'delete':
            case 'delete-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canDelete($params);
                } else {
                    $allowed = is_callable([$model, 'canDelete']) ? $model->canDelete() : true;
                }
                break;
            case 'index':
            case 'list':
                $query = null;
                if (array_key_exists('query', $params)) {
                    $query = $params['query'];
                    unset($params['query']);
                }
                $modelClass = $this->modelClass;
                if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
                    $allowed = $modelClass::canList($params, $query);
                } else {
                    $allowed = is_callable([$modelClass, 'canList']) ? $modelClass::canList($params, $query) : true;
                }
                break;
            case 'change-position':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canChangePosition($params);
                } else {
                    $allowed = is_callable([$model, 'canChangePosition']) ? $model->canChangePosition() : true;
                }
                break;
            default:
                $allowed = false;
        }
        if (!$allowed) {
            throw new ForbiddenHttpException;
        }
    }
}
