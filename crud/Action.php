<?php

namespace yii\mozayka\crud;

use yii\rest\Action as RestAction,
    yii\mozayka\helpers\ModelHelper,
    yii\db\BaseActiveRecord,
    yii\helpers\Inflector,
yii\kladovka\behaviors\DatetimeBehavior,
    yii\kladovka\behaviors\TimestampBehavior,
    yii\kladovka\behaviors\TimeDeleteBehavior,
    yii\kladovka\behaviors\SoftDeleteBehavior,
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
$columns = $this->columns ?: ModelHelper::gridColumns($modelClass);
$attributes = $model->attributes();
$columns = ModelHelper::normalizeBrackets(ModelHelper::expandBrackets($columns, $attributes), $attributes);
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
        foreach ($columns as $attribute => $options) {
            $options['attribute'] = $attribute;
            $columnSchema = $tableSchema->getColumn($attribute);
            if ($columnSchema) {
                /*if ($columnSchema->isPrimaryKey) {
                    $options['readOnly'] = true;
                }*/
                if (!array_key_exists('type', $options)) {
                    if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                        if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['type'] = 'boolean';
                        } else {
                            $options['type'] = $columnSchema->type;
                            $options['size'] = $columnSchema->size;
                            $options['unsigned'] = $columnSchema->unsigned;
                        }
                    } else {
                        $options['type'] = $columnSchema->type;
                    }
                }
            }
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
            if (!array_key_exists('class', $options)) {
                $methodName = Inflector::variablize($attribute) . 'ListItems';
                if (method_exists($modelClass, $methodName) && is_callable([$modelClass, $methodName])) {
                    $options['class'] = 'yii\mozayka\grid\ListItemColumn';
                    $options['items'] = $modelClass::$methodName($model);
                }
            }
            $columns[$attribute] = $options;
        }
        $columns[] = ['class' => 'yii\mozayka\grid\ActionColumn'];
        return array_values(array_filter($columns, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        }));
    }

    protected function prepareFields(BaseActiveRecord $model)
    {
        $modelClass = get_class($model);
$fields = $this->fields ?: ModelHelper::formFields($model);
$attributes = $model->attributes();
$fields = ModelHelper::normalizeBrackets(ModelHelper::expandBrackets($fields, $attributes), $attributes);
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
        foreach ($fields as $attribute => $options) {
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
                    if (in_array($columnSchema->type, ['tinyint', 'smallint', 'integer', 'bigint'])) {
                        if (($columnSchema->size == 1) && $columnSchema->unsigned) {
                            $options['type'] = 'boolean';
                        } else {
                            $options['type'] = $columnSchema->type;
                            $options['size'] = $columnSchema->size;
                            $options['unsigned'] = $columnSchema->unsigned;
                        }
                    } elseif (in_array($columnSchema->type, ['decimal', 'numeric', 'money'])) {
                        $options['type'] = $columnSchema->type;
                        $options['size'] = $columnSchema->size;
                        $options['scale'] = $columnSchema->scale;
                        $options['unsigned'] = $columnSchema->unsigned;
                    } else {
                        $options['type'] = $columnSchema->type;
                    }
                }
            }
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
            if (!array_key_exists('class', $options)) {
                $methodName = Inflector::variablize($attribute) . 'ListItems';
                if (method_exists($modelClass, $methodName) && is_callable([$modelClass, $methodName])) {
                    $options['class'] = 'yii\mozayka\form\DropDownListField';
                    $options['items'] = $modelClass::$methodName($model);
                }
            }
            $fields[$attribute] = $options;
        }
        return array_filter($fields, function ($options) {
            return !array_key_exists('visible', $options) || $options['visible'];
        });
    }
}
