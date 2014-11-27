<?php

namespace yii\mozayka\crud;

use yii\mozayka\helpers\ModelHelper;


class ChangePositionAction extends Action
{

    public function run($id = null)
    {
        //$modelClass = $this->modelClass;
        /** @var yii\db\ActiveRecordInterface $model */
        $model = $this->findModel($id);
        if (is_null($id)) {
            $id = ModelHelper::getPrimaryKey($model);
        }
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model, ['id' => $id]);
        }
        return __METHOD__;
    }
}
