<?php

namespace yii\mozayka\web;


class ActiveController extends Controller
{

    public function actions()
    {
        return [
            'create' => ['class' => CreateAction::className()],
            'read' => ['class' => ReadAction::className()],
            'update' => ['class' => UpdateAction::className()],
            'delete' => ['class' => DeleteAction::className()],
            'list' => ['class' => ListAction::className()]
        ];
    }
}
