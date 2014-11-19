<?php

namespace yii\mozayka\crud;

use yii\rest\Action as YiiAction,
    yii\base\Model,
    yii\db\ActiveRecord,
    yii\helpers\Inflector,
    yii\helpers\ArrayHelper,
    yii\kladovka\behaviors\TimestampBehavior,
    yii\kladovka\behaviors\TimeDeleteBehavior,
    yii\kladovka\behaviors\SoftDeleteBehavior,
    Yii;


class Action extends YiiAction
{

    public $columns = [];

    public $fields = [];

    public function findModel($id = null)
    {
        if (is_null($id)) {
            $modelClass = $this->modelClass;
            $emptyPrimaryKey = array_flip($modelClass::primaryKey());
            $primaryKey = array_merge($emptyPrimaryKey, array_intersect_key(Yii::$app->getRequest()->getQueryParams(), $emptyPrimaryKey));
            $id = implode(',', array_values($primaryKey));
        }
        return parent::findModel($id);
    }

    protected function prepareColumns(Model $model)
    {
        $modelClass = get_class($model);
        $attributes = array_keys($model->attributeLabels());
        if (!$attributes) {
            $attributes = $model->attributes();
        }
        $rawColumns = $this->columns;
        if (!$rawColumns && method_exists($model, 'attributeColumns') && is_callable([$model, 'attributeColumns'])) {
            $rawColumns = $model->attributeColumns();
        }
        $offset = array_search('*', $rawColumns);
        if ($offset !== false) {
            array_splice($rawColumns, $offset, 1, $attributes);
        } elseif (!$rawColumns) {
            $rawColumns = $attributes;
        }
        $tableSchema = ($model instanceof ActiveRecord) ? $model->getTableSchema() : null;
        $columns = [];
        foreach ($rawColumns as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if ($value) {
                    if (is_string($value) && in_array($value, $attributes)) {
                        $attribute = $value;
                    } elseif (is_array($value)) {
                        if (array_key_exists(0, $value) && $value[0] && is_string($value[0]) && in_array($value[0], $attributes)) {
                            $attribute = $value[0];
                            $options = $value;
                            unset($options[0]);
                        } elseif (array_key_exists('attribute', $value) && $value['attribute'] && is_string($value['attribute']) && in_array($value['attribute'], $attributes)) {
                            $attribute = $value['attribute'];
                            $options = $value;
                            unset($options['attribute']);
                        }
                    }
                }
            } elseif ($key && is_string($key) && in_array($key, $attributes)) {
                $attribute = $key;
                if ($value) {
                    if (is_string($value)) {
                        if ($value == 'invisible') {
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
                } elseif ($value === false) {
                    $options['visible'] = false;
                }
            }
            if ($attribute) {
                $options['attribute'] = $attribute;
                if (array_key_exists('type', $options)) {
                    if ($options['type'] && is_string($options['type'])) {
                        if ($options['type'] == 'invisible') {
                            $options['visible'] = false;
                        } elseif (!array_key_exists('class', $options)) {
                            $fieldClass = 'yii\mozayka\grid\\' . ucfirst($options['type']) . 'Column';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                    unset($options['type']);
                }
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        /*if ($columnSchema->isPrimaryKey) {
                            $options['readOnly'] = true;
                        }*/
                        if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                            if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                                $options['class'] = 'yii\mozayka\grid\BooleanColumn';
                            } else {
                                $options['class'] = 'yii\mozayka\grid\\' . ucfirst($columnSchema->type) . 'Column';
                                $options['size'] = $columnSchema->size;
                                $options['unsigned'] = $columnSchema->unsigned;
                            }
                        } else {
                            $fieldClass = 'yii\mozayka\grid\\' . ucfirst($columnSchema->type) . 'Column';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                }
                if (!array_key_exists('class', $options)) {
                    $methodName = Inflector::variablize($attribute) . 'ListItems';
                    if (method_exists($modelClass, $methodName) && is_callable([$modelClass, $methodName])) {
                        $options['class'] = 'yii\mozayka\grid\ListItemColumn';
                        $options['items'] = $modelClass::$methodName();
                    }
                }
                if (array_key_exists($attribute, $columns)) {
                    $columns[$attribute] = ArrayHelper::merge($columns[$attribute], $options);
                } else {
                    $columns[$attribute] = $options;
                }
            }
        }
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof SoftDeleteBehavior) {
                if (array_key_exists($behavior->deletedAttribute, $columns)) {
                    $columns[$behavior->deletedAttribute]['visible'] = false;
                }
            } elseif ($behavior instanceof TimeDeleteBehavior) {
                if (array_key_exists($behavior->deletedAtAttribute, $columns)) {
                    $columns[$behavior->deletedAtAttribute]['visible'] = false;
                }
            }
        }
        return array_values(array_filter($columns, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        }));
    }

    protected function prepareFields(Model $model)
    {
        $modelClass = get_class($model);
        $attributes = array_keys($model->attributeLabels());
        if (!$attributes) {
            $attributes = $model->attributes();
        }
        $rawFields = $this->fields;
        if (!$rawFields && method_exists($model, 'attributeFields') && is_callable([$model, 'attributeFields'])) {
            $rawFields = $model->attributeFields();
        }
        $offset = array_search('*', $rawFields);
        if ($offset !== false) {
            array_splice($rawFields, $offset, 1, $attributes);
        } elseif (!$rawFields) {
            $rawFields = $attributes;
        }
        $tableSchema = ($model instanceof ActiveRecord) ? $model->getTableSchema() : null;
        $fields = [];
        foreach ($rawFields as $key => $value) {
            $attribute = null;
            $options = [];
            if (is_int($key)) {
                if ($value) {
                    if (is_string($value) && in_array($value, $attributes)) {
                        $attribute = $value;
                    } elseif (is_array($value)) {
                        if (array_key_exists(0, $value) && $value[0] && is_string($value[0]) && in_array($value[0], $attributes)) {
                            $attribute = $value[0];
                            $options = $value;
                            unset($options[0]);
                        } elseif (array_key_exists('attribute', $value) && $value['attribute'] && is_string($value['attribute']) && in_array($value['attribute'], $attributes)) {
                            $attribute = $value['attribute'];
                            $options = $value;
                            unset($options['attribute']);
                        }
                    }
                }
            } elseif ($key && is_string($key) && in_array($key, $attributes)) {
                $attribute = $key;
                if ($value) {
                    if (is_string($value)) {
                        if ($value == 'invisible') {
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
                } elseif ($value === false) {
                    $options['visible'] = false;
                }
            }
            if ($attribute) {
                if (array_key_exists('type', $options)) {
                    if ($options['type'] && is_string($options['type'])) {
                        if ($options['type'] == 'invisible') {
                            $options['visible'] = false;
                        } elseif (!array_key_exists('class', $options)) {
                            $fieldClass = 'yii\mozayka\form\\' . ucfirst($options['type']) . 'Field';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                    unset($options['type']);
                }
                if ($tableSchema && !array_key_exists('class', $options)) {
                    $columnSchema = $tableSchema->getColumn($attribute);
                    if ($columnSchema) {
                        if ($columnSchema->isPrimaryKey && !method_exists($model, 'search')) {
                            if (!$model->getIsNewRecord()) {
                                $options['readOnly'] = true;
                            } elseif ($columnSchema->autoIncrement) {
                                $options['visible'] = false;
                            }
                        }
                        if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                            if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                                $options['class'] = 'yii\mozayka\form\BooleanField';
                            } else {
                                $options['class'] = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                                $options['size'] = $columnSchema->size;
                                $options['unsigned'] = $columnSchema->unsigned;
                            }
                        } elseif (in_array($columnSchema->type, ['decimal', 'numeric', 'money'])) {
                            $options['class'] = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                            $options['size'] = $columnSchema->size;
                            $options['scale'] = $columnSchema->scale;
                            $options['unsigned'] = $columnSchema->unsigned;
                        } else {
                            $fieldClass = 'yii\mozayka\form\\' . ucfirst($columnSchema->type) . 'Field';
                            if (class_exists($fieldClass)) {
                                $options['class'] = $fieldClass;
                            }
                        }
                    }
                }
                if (!array_key_exists('class', $options)) {
                    $methodName = Inflector::variablize($attribute) . 'ListItems';
                    if (method_exists($modelClass, $methodName) && is_callable([$modelClass, $methodName])) {
                        $options['class'] = 'yii\mozayka\form\DropDownListField';
                        $options['items'] = $modelClass::$methodName();
                    }
                }
                if (array_key_exists($attribute, $fields)) {
                    $fields[$attribute] = ArrayHelper::merge($fields[$attribute], $options);
                } else {
                    $fields[$attribute] = $options;
                }
            }
        }
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof SoftDeleteBehavior) {
                if (array_key_exists($behavior->deletedAttribute, $fields)) {
                    $fields[$behavior->deletedAttribute]['visible'] = false;
                }
            } elseif ($behavior instanceof TimeDeleteBehavior) {
                if (array_key_exists($behavior->deletedAtAttribute, $fields)) {
                    $fields[$behavior->deletedAtAttribute]['visible'] = false;
                }
            } elseif ($behavior instanceof TimestampBehavior) {
                if (array_key_exists($behavior->createdAtAttribute, $fields)) {
                    $fields[$behavior->createdAtAttribute]['readOnly'] = true;
                }
                if (array_key_exists($behavior->updatedAtAttribute, $fields)) {
                    $fields[$behavior->updatedAtAttribute]['readOnly'] = true;
                }
                if (array_key_exists($behavior->timestampAttribute, $fields)) {
                    $fields[$behavior->timestampAttribute]['readOnly'] = true;
                }
            }
        }
        return array_filter($fields, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        });
    }
}
