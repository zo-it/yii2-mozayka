<?php

namespace yii\mozayka\crud;

use yii\rest\Action as RestAction,
    yii\mozayka\helpers\ModelHelper,
    yii\kladovka\behaviors\DatetimeBehavior,
    yii\kladovka\behaviors\SoftDeleteBehavior,
    yii\kladovka\behaviors\TimeDeleteBehavior,
    yii\helpers\Inflector,
    yii\db\BaseActiveRecord,
    yii\kladovka\behaviors\TimestampBehavior,
    Yii;


class Action extends RestAction
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

    protected function prepareColumns($modelClass)
    {
        $model = new $modelClass;
        $validKeys = $model->attributes();
        $columns = ModelHelper::normalizeBrackets(ModelHelper::expandBrackets($this->columns ?: ModelHelper::gridColumns($modelClass), $validKeys), array_merge($validKeys, ['action-column']));
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof DatetimeBehavior) {
                foreach ($behavior->attributes as $datetimeAttribute) {
                    if (array_key_exists($datetimeAttribute, $columns) && !array_key_exists('type', $columns[$datetimeAttribute])) {
                        $columns[$datetimeAttribute]['type'] = 'datetime';
                    }
                }
            } elseif ($behavior instanceof SoftDeleteBehavior) {
                if (array_key_exists($behavior->deletedAttribute, $columns)) {
                    $columns[$behavior->deletedAttribute]['visible'] = false;
                }
            } elseif ($behavior instanceof TimeDeleteBehavior) {
                if (array_key_exists($behavior->deletedAtAttribute, $columns)) {
                    $columns[$behavior->deletedAtAttribute]['visible'] = false;
                }
            }
        }
        $tableSchema = $modelClass::getTableSchema();
        foreach ($tableSchema->foreignKeys as $foreignKey) {
            if (count($foreignKey) == 2) {
                $relatedQueryMethod = 'get' . Inflector::classify($foreignKey[0]);
                if (method_exists($model, $relatedQueryMethod) && is_callable([$model, $relatedQueryMethod])) {
                    $relatedAttribute = array_keys($foreignKey)[1];
                    $columns[$relatedAttribute]['type'] = 'listItem';
                    $columns[$relatedAttribute]['items'] = ModelHelper::listItems($model->$relatedQueryMethod());
                }
            }
        }
        if (!array_key_exists('action-column', $columns)) {
            $columns['action-column'] = ['type' => 'action'];
        }
        foreach ($columns as $attribute => $options) {
            $options['attribute'] = $attribute;
            if (!array_key_exists('type', $options)) {
                $listItemsMethod = Inflector::variablize($attribute) . 'ListItems';
                if (method_exists($modelClass, $listItemsMethod) && is_callable([$modelClass, $listItemsMethod])) {
                    $options['type'] = 'listItem';
                    $options['items'] = $modelClass::$listItemsMethod($model);
                }
            }
            $columnSchema = $tableSchema->getColumn($attribute);
            if ($columnSchema) {
                if ($columnSchema->isPrimaryKey) {
                    $options['readOnly'] = true;
                }
                if (!array_key_exists('type', $options)) {
                    $options['type'] = $columnSchema->type;
                    if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                        if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['type'] = 'boolean';
                        } else {
                            $options['size'] = $columnSchema->size;
                            $options['unsigned'] = $columnSchema->unsigned;
                        }
                    } elseif (in_array($columnSchema->type, ['decimal', 'numeric', 'money'])) {
                        $options['size'] = $columnSchema->size;
                        $options['scale'] = $columnSchema->scale;
                        $options['unsigned'] = $columnSchema->unsigned;
                    } elseif ($columnSchema->type == 'string') {
                        $options['size'] = $columnSchema->size;
                    }
                }
            }
            if (array_key_exists('type', $options)) {
                if ($options['type'] && is_string($options['type'])) {
                    if ($options['type'] == 'invisible') {
                        $options['visible'] = false;
                    } elseif (!array_key_exists('class', $options)) {
                        $fieldClass = 'yii\mozayka\grid\\' . Inflector::id2camel($options['type']) . 'Column';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        }
                    }
                }
                unset($options['type']);
            }
            $columns[$attribute] = $options;
        }
        unset($columns['action-column']['attribute']);
        return array_values(array_filter($columns, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        }));
    }

    protected function prepareFields(BaseActiveRecord $model)
    {
        $modelClass = get_class($model);
        $validKeys = $model->attributes();
        $fields = ModelHelper::normalizeBrackets(ModelHelper::expandBrackets($this->fields ?: ModelHelper::formFields($model), $validKeys), $validKeys);
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof DatetimeBehavior) {
                foreach ($behavior->attributes as $datetimeAttribute) {
                    if (array_key_exists($datetimeAttribute, $fields) && !array_key_exists('type', $fields[$datetimeAttribute])) {
                        $fields[$datetimeAttribute]['type'] = 'datetime';
                    }
                }
            } elseif ($behavior instanceof SoftDeleteBehavior) {
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
        $tableSchema = $modelClass::getTableSchema();
        foreach ($tableSchema->foreignKeys as $foreignKey) {
            if (count($foreignKey) == 2) {
                $relatedQueryMethod = 'get' . Inflector::classify($foreignKey[0]);
                if (method_exists($model, $relatedQueryMethod) && is_callable([$model, $relatedQueryMethod])) {
                    $relatedAttribute = array_keys($foreignKey)[1];
                    $fields[$relatedAttribute]['type'] = 'dropDownList';
                    $fields[$relatedAttribute]['items'] = ModelHelper::listItems($model->$relatedQueryMethod());
                }
            }
        }
        foreach ($fields as $attribute => $options) {
            if (!array_key_exists('type', $options)) {
                $listItemsMethod = Inflector::variablize($attribute) . 'ListItems';
                if (method_exists($modelClass, $listItemsMethod) && is_callable([$modelClass, $listItemsMethod])) {
                    $options['type'] = 'dropDownList';
                    $options['items'] = $modelClass::$listItemsMethod($model);
                }
            }
            $columnSchema = $tableSchema->getColumn($attribute);
            if ($columnSchema) {
                if ($columnSchema->isPrimaryKey && !method_exists($model, 'search')) {
                    if (!$model->getIsNewRecord()) {
                        $options['readOnly'] = true;
                    } elseif ($columnSchema->autoIncrement) {
                        $options['visible'] = false;
                    }
                }
                if (!array_key_exists('type', $options)) {
                    $options['type'] = $columnSchema->type;
                    if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                        if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['type'] = 'boolean';
                        } else {
                            $options['size'] = $columnSchema->size;
                            $options['unsigned'] = $columnSchema->unsigned;
                        }
                    } elseif (in_array($columnSchema->type, ['decimal', 'numeric', 'money'])) {
                        $options['size'] = $columnSchema->size;
                        $options['scale'] = $columnSchema->scale;
                        $options['unsigned'] = $columnSchema->unsigned;
                    } elseif ($columnSchema->type == 'string') {
                        $options['size'] = $columnSchema->size;
                    }
                }
            }
            if (array_key_exists('type', $options)) {
                if ($options['type'] && is_string($options['type'])) {
                    if ($options['type'] == 'invisible') {
                        $options['visible'] = false;
                    } elseif (!array_key_exists('class', $options)) {
                        $fieldClass = 'yii\mozayka\form\\' . Inflector::id2camel($options['type']) . 'Field';
                        if (class_exists($fieldClass)) {
                            $options['class'] = $fieldClass;
                        }
                    }
                }
                unset($options['type']);
            }
            $fields[$attribute] = $options;
        }
        return array_filter($fields, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        });
    }
}
