<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as YiiActiveRecord,
    Yii;


class ActiveRecord extends YiiActiveRecord
{

    const SCENARIO_CREATE = 'create';

    const SCENARIO_UPDATE = 'update';

    const SCENARIO_DELETE = 'delete';

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
        return static::hasRealPrimaryKey();
    }

    public function canRead($params = [])
    {
        return $this::hasPrimaryKey();
    }

    public function canUpdate($params = [])
    {
        return $this::hasRealPrimaryKey();
    }

    public function canDelete($params = [])
    {
        return $this::hasRealPrimaryKey();
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
