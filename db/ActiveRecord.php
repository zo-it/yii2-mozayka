<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as YiiActiveRecord,
    yii\mozayka\helpers\ModelHelper,
    Yii;


class ActiveRecord extends YiiActiveRecord
{

    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    public function attributeColumns()
    {
        return [];
    }

    public function attributeFields()
    {
        return [];
    }

    public static function humanName()
    {
        return ModelHelper::generateHumanName(get_called_class());
    }

    public static function pluralHumanName()
    {
        return ModelHelper::generatePluralHumanName(get_called_class());
    }

    public function caption()
    {
        return ModelHelper::generateCaption($this);
    }

    public static function canCreate($params = [], $newModel = null)
    {
        return (bool)static::getTableSchema()->primaryKey;
    }

    public function canRead($params = [])
    {
        return (bool)$this::primaryKey();
    }

    public function canUpdate($params = [])
    {
        return (bool)$this::getTableSchema()->primaryKey;
    }

    public function canDelete($params = [])
    {
        return (bool)$this::getTableSchema()->primaryKey;
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
