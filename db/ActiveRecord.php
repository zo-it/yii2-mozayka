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

    public static function displayField()
    {
        return static::primaryKey();
    }

    public function getDisplayField()
    {
        return ModelHelper::generateDisplayField($this);
    }

    public function gridColumns()
    {
        return [];
    }

    public function formFields()
    {
        return [];
    }

    public static function canCreate($params = [], $newModel = null)
    {
        return ModelHelper::hasRealPrimaryKey(get_called_class());
    }

    public function canRead($params = [])
    {
        return ModelHelper::hasPrimaryKey(get_class($this));
    }

    public function canUpdate($params = [])
    {
        return ModelHelper::hasRealPrimaryKey(get_class($this));
    }

    public function canDelete($params = [])
    {
        return ModelHelper::hasRealPrimaryKey(get_class($this));
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
