<?php

namespace yii\mozayka\db;

use yii\kladovka\db\ActiveRecord as YiiActiveRecord;


class ActiveRecord extends YiiActiveRecord
{

    public function attributeColumns()
    {
        return [];
    }

    public function attributeFields()
    {
        return [];
    }
}
