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

    public static function canCreate($params = [])
    {
        return true;
    }

    public function canRead($params = [])
    {
        return true;
    }

    public function canUpdate($params = [])
    {
        return true;
    }

    public function canDelete($params = [])
    {
        return true;
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
        return $this->canRead($params);
    }
}
