<?php

namespace yii\mozayka\crud;

use yii\rest\Action as YiiAction,
    yii\base\Model,
    yii\db\ActiveRecord,
    yii\helpers\ArrayHelper;


class Action extends YiiAction
{

    public $columns = [];

    public $fields = [];

    protected function prepareColumns(Model $model)
    {
        $tableSchema = null;
        if ($model instanceof ActiveRecord) {
            $tableSchema = $model->getTableSchema();
        }
        $attributes = array_keys($model->attributeLabels());
        if (!$attributes) {
            $attributes = $model->attributes();
        }
        $rawFields = $this->columns;
        if (!$rawFields && method_exists($model, 'attributeColumns')) {
            $rawFields = $model->attributeColumns();
        }
        if (!$rawFields) {
            $rawFields = $attributes;
        }
        $columns = [];
        foreach ($rawFields as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if (is_string($value) && in_array($value, $attributes)) {
                    $attribute = $value;
                } elseif (is_array($value)) {
                    if (array_key_exists(0, $value) && in_array($value[0], $attributes)) {
                        $attribute = $value[0];
                        $options = $value;
                        unset($options[0]);
                    } elseif (array_key_exists('attribute', $value) && in_array($value['attribute'], $attributes)) {
                        $attribute = $value['attribute'];
                        $options = $value;
                        unset($options['attribute']);
                    }
                }
            } elseif (is_string($key) && in_array($key, $attributes)) {
                $attribute = $key;
                if (is_string($value)) {
                    if ($value == 'skip') {
                        continue;
                    } elseif (class_exists($value)) {
                        $options['class'] = $value;
                    } else {
                        $fieldClass = 'yii\mozayka\grid\\' . ucfirst($value) . 'Column';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        } else {
                            $options['type'] = $value;
                        }
                    }
                } elseif (is_array($value)) {
                    $options = $value;
                }
            }
            if (array_key_exists('type', $options)) {
                if (!array_key_exists('class', $options)) {
                    $fieldClass = 'yii\mozayka\grid\\' . ucfirst($options['type']) . 'Column';
                    if (class_exists($fieldClass)) {
                        $options['class'] = $fieldClass;
                    }
                }
                unset($options['type']);
            }
            if ($attribute) {
                $options['attribute'] = $attribute;
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        if ($columnSchema->isPrimaryKey) {
                            if (!$model->getIsNewRecord()) {
                                $options['readOnly'] = true;
                            } elseif ($columnSchema->autoIncrement) {
                                //continue;
                            }
                        }
                        if (($columnSchema->type == 'smallint') && ($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['class'] = 'yii\mozayka\grid\BooleanColumn';
                        } else {
                            $fieldClass = 'yii\mozayka\grid\\' . ucfirst($columnSchema->type) . 'Column';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                }
                if (array_key_exists($attribute, $columns)) {
                    $columns[$attribute] = ArrayHelper::merge($columns[$attribute], $options);
                } else {
                    $columns[$attribute] = $options;
                }
            }
        }
        return array_values($columns);
    }

    protected function prepareFields(Model $model)
    {
        $tableSchema = null;
        if ($model instanceof ActiveRecord) {
            $tableSchema = $model->getTableSchema();
        }
        $attributes = array_keys($model->attributeLabels());
        if (!$attributes) {
            $attributes = $model->attributes();
        }
        $rawFields = $this->fields;
        if (!$rawFields && method_exists($model, 'attributeFields')) {
            $rawFields = $model->attributeFields();
        }
        if (!$rawFields) {
            $rawFields = $attributes;
        }
        $fields = [];
        foreach ($rawFields as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if (is_string($value) && in_array($value, $attributes)) {
                    $attribute = $value;
                } elseif (is_array($value)) {
                    if (array_key_exists(0, $value) && in_array($value[0], $attributes)) {
                        $attribute = $value[0];
                        $options = $value;
                        unset($options[0]);
                    } elseif (array_key_exists('attribute', $value) && in_array($value['attribute'], $attributes)) {
                        $attribute = $value['attribute'];
                        $options = $value;
                        unset($options['attribute']);
                    }
                }
            } elseif (is_string($key) && in_array($key, $attributes)) {
                $attribute = $key;
                if (is_string($value)) {
                    if ($value == 'skip') {
                        continue;
                    } elseif (class_exists($value)) {
                        $options['class'] = $value;
                    } else {
                        $fieldClass = 'yii\mozayka\form\\' . ucfirst($value) . 'Field';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        } else {
                            $options['type'] = $value;
                        }
                    }
                } elseif (is_array($value)) {
                    $options = $value;
                }
            }
            if (array_key_exists('type', $options)) {
                if (!array_key_exists('class', $options)) {
                    $fieldClass = 'yii\mozayka\form\\' . ucfirst($options['type']) . 'Field';
                    if (class_exists($fieldClass)) {
                        $options['class'] = $fieldClass;
                    }
                }
                unset($options['type']);
            }
            if ($attribute) {
                //$options['attribute'] = $attribute;
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        if ($columnSchema->isPrimaryKey) {
                            if (!$model->getIsNewRecord()) {
                                $options['readOnly'] = true;
                            } elseif ($columnSchema->autoIncrement) {
                                continue;
                            }
                        }
                        if (($columnSchema->type == 'smallint') && ($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['class'] = 'yii\mozayka\form\BooleanField';
                        } else {
                            $fieldClass = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                }
                if (array_key_exists($attribute, $fields)) {
                    $fields[$attribute] = ArrayHelper::merge($fields[$attribute], $options);
                } else {
                    $fields[$attribute] = $options;
                }
            }
        }
        return $fields;
    }
}
