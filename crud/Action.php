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
        $rawColumns = $this->columns;
        if (!$rawColumns && method_exists($model, 'attributeColumns')) {
            $rawColumns = $model->attributeColumns();
        }
        $offset = array_search('*', $rawColumns);
        if ($offset !== false) {
            array_splice($rawColumns, $offset, 1, $attributes);
        } elseif (!$rawColumns) {
            $rawColumns = $attributes;
        }
        $columns = [];
        foreach ($rawColumns as $key => $value) {
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
                        $options['visible'] = false;
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
            if ($attribute) {
                $options['attribute'] = $attribute;
                if (array_key_exists('type', $options)) {
                    if ($options['type'] == 'skip') {
                        $options['visible'] = false;
                    } elseif (!array_key_exists('class', $options)) {
                        $fieldClass = 'yii\mozayka\grid\\' . ucfirst($options['type']) . 'Column';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        }
                    }
                    unset($options['type']);
                }
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        if ($columnSchema->isPrimaryKey) {
                            $options['readOnly'] = true;
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
        return array_values(array_filter($columns, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        }));
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
        $offset = array_search('*', $rawFields);
        if ($offset !== false) {
            array_splice($rawFields, $offset, 1, $attributes);
        } elseif (!$rawFields) {
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
                        $options['visible'] = false;
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
            if ($attribute) {
                if (array_key_exists('type', $options)) {
                    if ($options['type'] == 'skip') {
                        $options['visible'] = false;
                    } elseif (!array_key_exists('class', $options)) {
                        $fieldClass = 'yii\mozayka\form\\' . ucfirst($options['type']) . 'Field';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        }
                    }
                    unset($options['type']);
                }
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        if ($columnSchema->isPrimaryKey) {
                            if (!$model->getIsNewRecord()) {
                                $options['readOnly'] = true;
                            } elseif ($columnSchema->autoIncrement) {
                                $options['visible'] = false;
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
        return array_filter($fields, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        });
    }
}
