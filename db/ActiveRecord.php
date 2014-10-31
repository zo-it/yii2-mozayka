<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as YiiActiveRecord,
    Yii;


class ActiveRecord extends YiiActiveRecord
{

    public static function canCreate($model = null, $params = [])
    {
        return true;
    }

    public static function canRead($model = null, $params = [])
    {
        return true;
    }

    public static function canUpdate($model = null, $params = [])
    {
        return true;
    }

    public static function canDelete($model = null, $params = [])
    {
        return true;
    }

    public static function canList($query = null, $params = [])
    {
        return true;
    }

    public static function canChangePosition($model = null, $params = [])
    {
        return static::canUpdate($model, $params);
    }

    public static function canSelect($model = null, $params = [])
    {
        return static::canRead($model, $params);
    }

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
}
