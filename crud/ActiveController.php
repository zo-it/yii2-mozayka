<?php

namespace yii\mozayka\crud;

use yii\rest\ActiveController as YiiActiveController,
    yii\helpers\StringHelper,
    yii\mozayka\db\ActiveRecord,
    yii\web\ForbiddenHttpException,
    Yii;


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
            $filterModelClass = 'app\models\search\\' . $this->basename . 'Search';
            if (class_exists($filterModelClass)) {
                $this->filterModelClass = $filterModelClass;
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
                    $allowed = method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate($params, $newModel) : true;
                }
                break;
            case 'view':
            case 'read-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canRead($params);
                } else {
                    $allowed = method_exists($model, 'canRead') && is_callable([$model, 'canRead']) ? $model->canRead() : true;
                }
                break;
            case 'update':
            case 'update-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canUpdate($params);
                } else {
                    $allowed = method_exists($model, 'canUpdate') && is_callable([$model, 'canUpdate']) ? $model->canUpdate() : true;
                }
                break;
            case 'delete':
            case 'delete-form':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canDelete($params);
                } else {
                    $allowed = method_exists($model, 'canDelete') && is_callable([$model, 'canDelete']) ? $model->canDelete() : true;
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
                    $allowed = method_exists($modelClass, 'canList') && is_callable([$modelClass, 'canList']) ? $modelClass::canList($params, $query) : true;
                }
                break;
            case 'change-position':
                if ($model instanceof ActiveRecord) { // yii\mozayka\db\ActiveRecord
                    $allowed = $model->canChangePosition($params);
                } else {
                    $allowed = method_exists($model, 'canChangePosition') && is_callable([$model, 'canChangePosition']) ? $model->canChangePosition() : true;
                }
                break;
            default:
                $allowed = false;
        }
        if (!$allowed) {
            $user = Yii::$app->getUser();
            throw new ForbiddenHttpException(Yii::t('mozayka', 'Permission denied for user "{user}" to perform action "{action}".', [
                'user' => $user->getIsGuest() ? 'guest' : $user->getId(),
                'action' => $this->id . '/' . $action
            ]));
        }
    }
}
