<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as YiiActiveRecord,
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

    public static function canCreate($params = [], $newModel = null)
    {
        return (bool)static::getTableSchema()->primaryKey;
    }

    public function canRead($params = [])
    {
        return true;
    }

    public function canUpdate($params = [])
    {
        $modelClass = get_class($this);
        return (bool)$modelClass::getTableSchema()->primaryKey;
    }

    public function canDelete($params = [])
    {
        $modelClass = get_class($this);
        return (bool)$modelClass::getTableSchema()->primaryKey;
    }

    public static function canList($params = [], $query = null)
    {
        return true;
    }

    public function canChangePosition($params = [])
    {
        return $this->canUpdate($params);
    }

    public function canSelect($params = [])
    {
        $modelClass = get_class($this);
        return (bool)$modelClass::getTableSchema()->primaryKey || $this->canRead($params);
    }
}
