<?php

namespace yii\mozayka\crud;


class ChangePositionAction extends Action
{

    public function run($id = null)
    {
        /* @var yii\db\ActiveRecord $model */
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        return __METHOD__;
    }
}
