<?php

namespace yii\mozayka\crud;

use yii\rest\Action as YiiAction,
    yii\db\ActiveRecord;


class Action extends YiiAction
{

    public $fields = [];

    protected function buildFields(ActiveRecord $model)
    {
        $labels = $model->attributeLabels();
        $fields = $this->fields;
        if (!$fields && method_exists($model, 'attributeFields')) {
            $fields = $model->attributeFields();
        }
        if (!$fields) {
            if ($labels) {
                $fields = array_keys($labels);
            } else {
                $fields = $model->attributes();
            }
        }
        $tableSchema = $model->getTableSchema();
        $fields2 = [];
        foreach ($fields as $key => $value) {
            $fieldName = null;
            $fieldConfig = [];
            if (is_int($key)) {
                if (is_string($value) && $model->hasAttribute($value)) {
                    $fieldName = $value;
                } elseif (is_array($value)) {
                    if (array_key_exists(0, $value) && $model->hasAttribute($value[0])) {
                        $fieldName = $value[0];
                        $fieldConfig = $value;
                        unset($fieldConfig[0]);
                    } elseif (array_key_exists('name', $value) && $model->hasAttribute($value['name'])) {
                        $fieldName = $value['name'];
                        $fieldConfig = $value;
                        unset($fieldConfig['name']);
                    }
                }
            } elseif (is_string($key) && $model->hasAttribute($key)) {
                $fieldName = $key;
                if (is_string($value)) {
                    if (class_exists($value)) {
                        $fieldConfig['class'] = $value;
                    } else {
                        $fieldClass = 'yii\mozayka\form\\' . ucfirst($value) . 'Field';
                        if (class_exists($fieldClass)) {
                            $fieldConfig['class'] = $fieldClass;
                        }
                    }
                } elseif (is_array($value)) {
                    $fieldConfig = $value;
                }
            }
            if (array_key_exists('type', $fieldConfig)) {
                $fieldClass = 'yii\mozayka\form\\' . ucfirst($fieldConfig['type']) . 'Field';
                if (class_exists($fieldClass)) {
                    $fieldConfig['class'] = $fieldClass;
                }
                unset($fieldConfig['type']);
            }
            if (!array_key_exists('class', $fieldConfig)) {
                $columnSchema = $tableSchema->getColumn($fieldName);
                if ($columnSchema) {
                    $fieldClass = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                    if (class_exists($fieldClass)) {
                        $fieldConfig['class'] = $fieldClass;
                    }
                }
            }
            if ($fieldName) {
                $fields2[$fieldName] = $fieldConfig;
            }
        }
        return $fields2;
    }
}
