<?php

namespace yii\mozayka\helpers;

use yii\helpers\StringHelper,
    yii\helpers\Inflector,
    yii\mozayka\db\ActiveRecord as MozaykaActiveRecord,
    yii\db\ActiveRecordInterface,
    yii\kladovka\helpers\Log;


class BaseModelHelper
{

    public static function generateHumanName($modelClass)
    {
        return Inflector::camel2words(StringHelper::basename($modelClass));
    }

    public static function humanName($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::humanName();
        } else {
            return method_exists($modelClass, 'humanName') && is_callable([$modelClass, 'humanName']) ? $modelClass::humanName() : static::generateHumanName($modelClass);
        }
    }

    public static function generatePluralHumanName($modelClass)
    {
        return Inflector::pluralize(static::generateHumanName($modelClass));
    }

    public static function pluralHumanName($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::pluralHumanName();
        } else {
            return method_exists($modelClass, 'pluralHumanName') && is_callable([$modelClass, 'pluralHumanName']) ? $modelClass::pluralHumanName() : static::generatePluralHumanName($modelClass);
        }
    }

    public static function primaryKey(ActiveRecordInterface $model, $glue = ',')
    {
        return implode($glue, array_values($model->getPrimaryKey(true)));
    }

    public static function generateDisplayValue(ActiveRecordInterface $model)
    {
        return '#' . static::primaryKey($model);
    }

    public static function displayValue(ActiveRecordInterface $model)
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->getDisplayValue();
        } else {
            return method_exists($model, 'getDisplayValue') && is_callable([$model, 'getDisplayValue']) ? $model->getDisplayValue() : static::generateDisplayValue($model);
        }
    }

    public static function canCreate($modelClass, $params = [], $newModel = null)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::canCreate($params, $newModel);
        } else {
            return method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate($params, $newModel) : (bool)$modelClass::getTableSchema()->primaryKey;
        }
    }

    public static function canRead(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canRead($params);
        } else {
            return method_exists($model, 'canRead') && is_callable([$model, 'canRead']) ? $model->canRead($params) : (bool)$model::primaryKey();
        }
    }

    public static function canUpdate(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canUpdate($params);
        } else {
            return method_exists($model, 'canUpdate') && is_callable([$model, 'canUpdate']) ? $model->canUpdate($params) : (bool)$model::getTableSchema()->primaryKey;
        }
    }

    public static function canDelete(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canDelete($params);
        } else {
            return method_exists($model, 'canDelete') && is_callable([$model, 'canDelete']) ? $model->canDelete($params) : (bool)$model::getTableSchema()->primaryKey;
        }
    }

    public static function canList($modelClass, $params = [], $query = null)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::canList($params, $query);
        } else {
            return method_exists($modelClass, 'canList') && is_callable([$modelClass, 'canList']) ? $modelClass::canList($params, $query) : true;
        }
    }

    public static function canSelect(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canSelect($params);
        } else {
            return method_exists($model, 'canSelect') && is_callable([$model, 'canSelect']) ? $model->canSelect($params) : (bool)$model::primaryKey();
        }
    }

    public static function canChangePosition(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canChangePosition($params);
        } else {
            return method_exists($model, 'canChangePosition') && is_callable([$model, 'canChangePosition']) ? $model->canChangePosition($params) : (bool)$model::getTableSchema()->primaryKey;
        }
    }

    public static function log(ActiveRecordInterface $model, $message = null, $category = 'application')
    {
        if ($model->hasErrors()) {
            Log::error([
                'class' => get_class($model),
                'message' => $message,
                'attributes' => $model->getAttributes(),
                'errors' => $model->getErrors()
            ], $category);
        } else {
            Log::info([
                'class' => get_class($model),
                'message' => $message,
                'attributes' => $model->getAttributes()
            ], $category);
        }
    }
}