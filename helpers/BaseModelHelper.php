<?php

namespace yii\mozayka\helpers;

use yii\helpers\StringHelper,
    yii\helpers\Inflector,
    yii\mozayka\db\ActiveRecord as MozaykaActiveRecord,
    yii\db\BaseActiveRecord,
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
            $gridColumns = $modelClass::gridColumns();
        } else {
            $gridColumns = method_exists($modelClass, 'gridColumns') && is_callable([$modelClass, 'gridColumns']) ? $modelClass::gridColumns() : ['*'];
        }
        return static::expandBrackets($gridColumns, array_keys($modelClass::getTableSchema()->columns));
    }

    public static function getPrimaryKey(BaseActiveRecord $model, $separator = ',')
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

    public static function generateDisplayField(BaseActiveRecord $model)
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

    public static function getDisplayField(BaseActiveRecord $model)
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->getDisplayField();
        } else {
            return method_exists($model, 'getDisplayField') && is_callable([$model, 'getDisplayField']) ? $model->getDisplayField() : static::generateDisplayField($model);
        }
    }

    public static function formFields(BaseActiveRecord $model)
    {
        if ($model instanceof MozaykaActiveRecord) {
            $formFields = $model->formFields();
        } else {
            $formFields = method_exists($model, 'formFields') && is_callable([$model, 'formFields']) ? $model->formFields() : ['*'];
        }
        return static::expandBrackets($formFields, $model->attributes());
    }

    protected static function expandBrackets(array $input, array $modelAttributes)
    {
        $result = [];
        foreach ($input as $key => $value) {
            if (is_int($key)) {
                if (is_array($value) && (count($value) == 2) && array_key_exists(0, $value) && array_key_exists(1, $value)) {
                    if ($value[0] == '*') {
                        $k1 = 0;
                    } else {
                        $k1 = array_search($value[0], $modelAttributes);
                    }
                    if ($value[1] == '*') {
                        $k2 = count($modelAttributes) - 1;
                    } else {
                        $k2 = array_search($value[1], $modelAttributes);
                    }
                    if (is_int($k1) && is_int($k2) && ($k1 <= $k2) && array_key_exists($k1, $modelAttributes) && array_key_exists($k2, $modelAttributes)) {
                        $result = array_merge($result, array_slice($modelAttributes, $k1, $k2 - $k1 + 1));
                    }
                } elseif (($value == '*') || ($value == ['*'])) {
                    $result = array_merge($result, $modelAttributes);
                } else {
                    $result[] = $value;
                }
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function canCreate($modelClass, $params = [], $newModel = null)
    {
        if (is_subclass_of($modelClass, MozaykaActiveRecord::className())) {
            return $modelClass::canCreate($params, $newModel);
        } else {
            return method_exists($modelClass, 'canCreate') && is_callable([$modelClass, 'canCreate']) ? $modelClass::canCreate($params, $newModel) : (bool)$modelClass::getTableSchema()->primaryKey;
        }
    }

    public static function canRead(BaseActiveRecord $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canRead($params);
        } else {
            return method_exists($model, 'canRead') && is_callable([$model, 'canRead']) ? $model->canRead($params) : (bool)$model::primaryKey();
        }
    }

    public static function canUpdate(BaseActiveRecord $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canUpdate($params);
        } else {
            return method_exists($model, 'canUpdate') && is_callable([$model, 'canUpdate']) ? $model->canUpdate($params) : (bool)$model::getTableSchema()->primaryKey;
        }
    }

    public static function canDelete(BaseActiveRecord $model, $params = [])
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

    public static function canSelect(BaseActiveRecord $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canSelect($params);
        } else {
            return method_exists($model, 'canSelect') && is_callable([$model, 'canSelect']) ? $model->canSelect($params) : (bool)$model::primaryKey();
        }
    }

    public static function canChangePosition(BaseActiveRecord $model, $params = [])
    {
        if ($model instanceof MozaykaActiveRecord) {
            return $model->canChangePosition($params);
        } else {
            return method_exists($model, 'canChangePosition') && is_callable([$model, 'canChangePosition']) ? $model->canChangePosition($params) : (bool)$model::getTableSchema()->primaryKey;
        }
    }

    public static function dump(BaseActiveRecord $model)
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

    public static function log(BaseActiveRecord $model, $message = null, $category = 'application')
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
