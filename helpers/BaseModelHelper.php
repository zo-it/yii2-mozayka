<?php

namespace yii\mozayka\helpers;

use yii\db\ActiveRecordInterface,
    yii\kladovka\helpers\Log,
    yii\mozayka\db\ActiveRecord;


class BaseModelHelper
{

    public static function implodePrimaryKey(ActiveRecordInterface $model, $glue = ',')
    {
        return implode($glue, array_values($model->getPrimaryKey(true)));
    }

    public static function caption(ActiveRecordInterface $model)
    {
        return '#' . static::implodePrimaryKey($model);
    }

    public static function log(ActiveRecordInterface $model, $message = null, $category = 'application')
    {
        if ($model->hasErrors()) {
            if (!is_null($message)) {
                Log::error($message, $category);
            }
            Log::error([
                'class' => get_class($model),
                'attributes' => $model->getAttributes(),
                'errors' => $model->getErrors()
            ], $category);
        } else {
            if (!is_null($message)) {
                Log::info($message, $category);
            }
            Log::info([
                'class' => get_class($model),
                'attributes' => $model->getAttributes()
            ], $category);
        }
    }

    public static function canList($modelClass, $params = [], $query = null)
    {
        if (is_subclass_of($modelClass, ActiveRecord::className())) { // yii\mozayka\db\ActiveRecord
            return $modelClass::canList($params, $query);
        } else {
            return method_exists($modelClass, 'canList') && is_callable([$modelClass, 'canList']) ? $modelClass::canList($params, $query) : true;
        }
    }
}
