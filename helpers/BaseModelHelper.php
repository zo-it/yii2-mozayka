<?php

namespace yii\mozayka\helpers;

use yii\helpers\StringHelper,
    yii\helpers\Inflector,
    yii\mozayka\db\ActiveRecord as MozaykaActiveRecord,
    yii\db\ActiveRecordInterface,
    yii\helpers\VarDumper,
    Yii;


class BaseModelHelper
{

    public static function generateHumanName($modelClass)
    {
        return Yii::t('app', Inflector::camel2words(StringHelper::basename($modelClass)));
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
        return Yii::t('app', Inflector::pluralize(Inflector::camel2words(StringHelper::basename($modelClass))));
    }

    public static function pluralHumanName($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::pluralHumanName();
        } else {
            return method_exists($modelClass, 'pluralHumanName') && is_callable([$modelClass, 'pluralHumanName']) ? $modelClass::pluralHumanName() : static::generatePluralHumanName($modelClass);
        }
    }

    public static function gridConfig($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::gridConfig();
        } else {
            return method_exists($modelClass, 'gridConfig') && is_callable([$modelClass, 'gridConfig']) ? $modelClass::gridConfig() : [];
        }
    }

    public static function gridColumns($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::gridColumns();
        } else {
            return method_exists($modelClass, 'gridColumns') && is_callable([$modelClass, 'gridColumns']) ? $modelClass::gridColumns() : [];
        }
    }

    public static function getPrimaryKey(ActiveRecordInterface $model, $separator = ',')
    {
        return implode($separator, array_values($model->getPrimaryKey(true)));
    }

    public static function displayField($modelClass)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::displayField();
        } else {
            return method_exists($modelClass, 'displayField') && is_callable([$modelClass, 'displayField']) ? $modelClass::displayField() : $modelClass::primaryKey();
        }
    }

    public static function generateDisplayField(ActiveRecordInterface $model)
    {
        $separator = ' ';
        $attributes = static::displayField(get_class($model));
        if (array_key_exists('separator', $attributes)) {
            $separator = $attributes['separator'];
            unset($attributes['separator']);
        }
        $emptyDisplayField = array_flip($attributes);
        $displayField = array_merge($emptyDisplayField, array_intersect_key($model->getAttributes(), $emptyDisplayField));
        return implode($separator, array_values($displayField));
    }

    public static function getDisplayField(ActiveRecordInterface $model)
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->getDisplayField();
        } else {
            return method_exists($model, 'getDisplayField') && is_callable([$model, 'getDisplayField']) ? $model->getDisplayField() : static::generateDisplayField($model);
        }
    }

    public static function formFields(ActiveRecordInterface $model)
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->formFields();
        } else {
            return method_exists($model, 'formFields') && is_callable([$model, 'formFields']) ? $model->formFields() : [];
        }
    }

    public static function hasRealPrimaryKey($modelClass)
    {
        return (bool)$modelClass::getTableSchema()->primaryKey;
    }

    public static function hasPrimaryKey($modelClass)
    {
        return (bool)$modelClass::primaryKey();
    }

    public static function canCreate($modelClass, $params = [], $newModel = null)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::canCreate($params, $newModel);
        } else {
            return method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate($params, $newModel) : static::hasRealPrimaryKey($modelClass);
        }
    }

    public static function canRead(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canRead($params);
        } else {
            return method_exists($model, 'canRead') && is_callable([$model, 'canRead']) ? $model->canRead($params) : static::hasPrimaryKey(get_class($model));
        }
    }

    public static function canUpdate(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canUpdate($params);
        } else {
            return method_exists($model, 'canUpdate') && is_callable([$model, 'canUpdate']) ? $model->canUpdate($params) : static::hasRealPrimaryKey(get_class($model));
        }
    }

    public static function canDelete(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canDelete($params);
        } else {
            return method_exists($model, 'canDelete') && is_callable([$model, 'canDelete']) ? $model->canDelete($params) : static::hasRealPrimaryKey(get_class($model));
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
            return method_exists($model, 'canSelect') && is_callable([$model, 'canSelect']) ? $model->canSelect($params) : static::hasPrimaryKey(get_class($model));
        }
    }

    public static function canChangePosition(ActiveRecordInterface $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canChangePosition($params);
        } else {
            return method_exists($model, 'canChangePosition') && is_callable([$model, 'canChangePosition']) ? $model->canChangePosition($params) : static::hasRealPrimaryKey(get_class($model));
        }
    }

    public static function dump(ActiveRecordInterface $model)
    {
        if ($model->hasErrors()) {
            VarDumper::dump([
                'class' => get_class($model),
                'attributes' => $model->getAttributes(),
                'errors' => $model->getErrors()
            ]);
        } else {
            VarDumper::dump([
                'class' => get_class($model),
                'attributes' => $model->getAttributes()
            ]);
        }
    }

    public static function log(ActiveRecordInterface $model, $message = null, $category = 'application')
    {
        if ($model->hasErrors()) {
            Yii::error(VarDumper::dumpAsString([
                'class' => get_class($model),
                'message' => $message,
                'attributes' => $model->getAttributes(),
                'errors' => $model->getErrors()
            ]), $category);
        } else {
            Yii::info(VarDumper::dumpAsString([
                'class' => get_class($model),
                'message' => $message,
                'attributes' => $model->getAttributes()
            ]), $category);
        }
    }
}
