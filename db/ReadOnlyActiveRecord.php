<?php

namespace yii\mozayka\db;


class ReadOnlyActiveRecord extends ActiveRecord
{

    public static function canCreate($params = [], $newModel = null)
    {
        return false;
    }

    public function canUpdate($params = [])
    {
        return false;
    }

    public function canDelete($params = [])
    {
        return false;
    }
}
