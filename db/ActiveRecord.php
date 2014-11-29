<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as KladovkaActiveRecord,
    yii\mozayka\helpers\ModelHelper,
    Yii;


class ActiveRecord extends KladovkaActiveRecord
{

    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    public static function humanName()
    {
        return ModelHelper::generateHumanName(get_called_class());
    }

    public static function pluralHumanName()
    {
        return ModelHelper::generatePluralHumanName(get_called_class());
    }

    public static function gridConfig()
    {
        return [];
    }

    public static function gridColumns()
    {
        return ['*'];
    }

    public static function displayField()
    {
        return static::primaryKey();
    }

    public function getDisplayField()
    {
        return ModelHelper::generateDisplayField($this);
    }

    public function formFields()
    {
        return ['*'];
    }

    public static function canCreate($params = [], $newModel = null)
    {
        return (bool)static::getTableSchema()->primaryKey;
    }

    public function canRead($params = [])
    {
        return (bool)static::primaryKey();
    }

    public function canUpdate($params = [])
    {
        return (bool)static::getTableSchema()->primaryKey;
    }

    public function canDelete($params = [])
    {
        return (bool)static::getTableSchema()->primaryKey;
    }

    public static function canList($params = [], $query = null)
    {
        return true;
    }

    public function canSelect($params = [])
    {
        return $this->canRead($params);
    }

    public function canChangePosition($params = [])
    {
        return $this->canUpdate($params);
    }
}
