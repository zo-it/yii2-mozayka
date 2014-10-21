<?php

namespace yii\mozayka\crud;

use yii\rest\Action as YiiAction,
    yii\db\ActiveRecord;


class Action extends YiiAction
{

    public $fields = [];

    protected function prepareFields(ActiveRecord $model)
    {
        $labels = $model->attributeLabels();
        $tableSchema = $model->getTableSchema();
        $rawFields = $this->fields;
        if (!$rawFields && method_exists($model, 'attributeFields')) {
            $rawFields = $model->attributeFields();
        }
        if (!$rawFields) {
            if ($labels) {
                $rawFields = array_keys($labels);
            } else {
                $rawFields = $model->attributes();
            }
        }
        $fields = [];
        foreach ($rawFields as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if (is_string($value) && $model->hasAttribute($value)) {
                    $attribute = $value;
                } elseif (is_array($value)) {
                    if (array_key_exists(0, $value) && $model->hasAttribute($value[0])) {
                        $attribute = $value[0];
                        $options = $value;
                        unset($options[0]);
                    } elseif (array_key_exists('name', $value) && $model->hasAttribute($value['name'])) {
                        $attribute = $value['name'];
                        $options = $value;
                        unset($options['name']);
                    }
                }
            } elseif (is_string($key) && $model->hasAttribute($key)) {
                $attribute = $key;
                if (is_string($value)) {
                    if (class_exists($value)) {
                        $options['class'] = $value;
                    } else {
                        $fieldClass = 'yii\mozayka\form\\' . ucfirst($value) . 'Field';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        }
                    }
                } elseif (is_array($value)) {
                    $options = $value;
                }
            }
            if (array_key_exists('type', $options)) {
                $fieldClass = 'yii\mozayka\form\\' . ucfirst($options['type']) . 'Field';
                if (class_exists($fieldClass)) {
                    $options['class'] = $fieldClass;
                }
                unset($options['type']);
            }
            if (!array_key_exists('class', $options)) {
                $columnSchema = $tableSchema->getColumn($attribute);
                if ($columnSchema) {
                    $fieldClass = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                    if (class_exists($fieldClass)) {
                        $options['class'] = $fieldClass;
                    }
                }
            }
            if ($attribute) {
                $fields[$attribute] = $options;
            }
        }
        return $fields;
    }
}
