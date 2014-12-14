<?php

namespace yii\mozayka\helpers;

use yii\helpers\StringHelper,
    yii\helpers\Inflector,
    yii\mozayka\db\ActiveRecord as MozaykaActiveRecord,
    yii\db\BaseActiveRecord,
    yii\base\Object,
    yii\db\ActiveQuery,
    yii\helpers\ArrayHelper,
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
            return method_exists($modelClass, 'gridColumns') && is_callable([$modelClass, 'gridColumns']) ? $modelClass::gridColumns() : ['*'];
        }
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
        $attributes = static::displayField(get_class($model));
        $format = null;
        if (array_key_exists('format', $attributes)) {
            $format = $attributes['format'];
            unset($attributes['format']);
        }
        $separator = ' ';
        if (array_key_exists('separator', $attributes)) {
            $separator = $attributes['separator'];
            unset($attributes['separator']);
        }
        $emptyDisplayField = array_flip($attributes);
        $displayField = array_merge($emptyDisplayField, array_intersect_key($model->getAttributes(), $emptyDisplayField));
        if ($format) {
            return vsprintf($format, array_values($displayField));
        } else {
            return implode($separator, array_values($displayField));
        }
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
            return $model->formFields();
        } else {
            return method_exists($model, 'formFields') && is_callable([$model, 'formFields']) ? $model->formFields() : ['*'];
        }
    }

    public static function expandBrackets(array $input, array $validKeys)
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (is_int($key)) {
                if (is_array($value) && (count($value) == 2) && array_key_exists(0, $value) && array_key_exists(1, $value)) {
                    if ($value[0] == '*') {
                        $start = 0;
                    } else {
                        $start = array_search($value[0], $validKeys);
                    }
                    if ($value[1] == '*') {
                        $end = count($validKeys) - 1;
                    } else {
                        $end = array_search($value[1], $validKeys);
                    }
                    if (is_int($start) && is_int($end) && ($start <= $end) && array_key_exists($start, $validKeys) && array_key_exists($end, $validKeys)) {
                        $output = array_merge($output, array_slice($validKeys, $start, $end - $start + 1));
                    }
                } elseif (($value == '*') || ($value == ['*'])) {
                    $output = array_merge($output, $validKeys);
                } else {
                    $output[] = $value;
                }
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }

    public static function normalizeBrackets(array $input, array $validKeys)
    {
        $output = [];
        foreach ($input as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if ($value) {
                    if (is_string($value) && in_array($value, $validKeys)) {
                        $attribute = $value;
                    } elseif (is_array($value)) {
                        if (array_key_exists(0, $value) && $value[0] && is_string($value[0]) && in_array($value[0], $validKeys)) {
                            $attribute = $value[0];
                            $options = $value;
                            unset($options[0]);
                        } elseif (array_key_exists('attribute', $value) && $value['attribute'] && is_string($value['attribute']) && in_array($value['attribute'], $validKeys)) {
                            $attribute = $value['attribute'];
                            $options = $value;
                            unset($options['attribute']);
                        }
                    }
                }
            } elseif ($key && is_string($key) && in_array($key, $validKeys)) {
                $attribute = $key;
                if ($value) {
                    if (is_string($value)) {
                        if ($value == 'invisible') {
                            $options['visible'] = false;
                        } elseif (class_exists($value) && is_subclass_of($value, Object::className())) {
                            $options['class'] = $value;
                        } else {
                            $options['type'] = $value;
                        }
                    } elseif (is_array($value)) {
                        $options = $value;
                    }
                } elseif ($value === false) {
                    $options['visible'] = false;
                }
            }
            if (array_key_exists($attribute, $output)) {
                $output[$attribute] = ArrayHelper::merge($output[$attribute], $options);
            } else {
                $output[$attribute] = $options;
            }
        }
        return $output;
    }

    public static function listItems(ActiveQuery $query)
    {
        $listItems = [];
        $query->primaryModel = null;
        $query->link = null;
        $query->multiple = null;
        $modelClass = $query->modelClass;
        if (!static::canList($modelClass, [], $query)) {
            return [];
        }
        $emptyPrimaryKey = array_flip($modelClass::primaryKey());
        $attributes = static::displayField($modelClass);
        $format = null;
        if (array_key_exists('format', $attributes)) {
            $format = $attributes['format'];
            unset($attributes['format']);
        }
        $separator = ' ';
        if (array_key_exists('separator', $attributes)) {
            $separator = $attributes['separator'];
            unset($attributes['separator']);
        }
        $emptyDisplayField = array_flip($attributes);
        foreach ($query->asArray()->all() as $modelAttributes) {
            $primaryKey = array_merge($emptyPrimaryKey, array_intersect_key($modelAttributes, $emptyPrimaryKey));
            $id = implode(',', array_values($primaryKey));
            $displayField = array_merge($emptyDisplayField, array_intersect_key($modelAttributes, $emptyDisplayField));
            if ($format) {
                $listItems[$id] = vsprintf($format, array_values($displayField));
            } else {
                $listItems[$id] = implode($separator, array_values($displayField));
            }
        }
        return $listItems;
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

    public static function dumpAsString(BaseActiveRecord $model)
    {
        if ($model->hasErrors()) {
            VarDumper::dumpAsString([
                'class' => get_class($model),
                'attributes' => $model->getAttributes(),
                'errors' => $model->getErrors()
            ]);
        } else {
            VarDumper::dumpAsString([
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
