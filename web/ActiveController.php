<?php

namespace yii\mozayka\web;


class ActiveController extends Controller
{

    public function actions()
    {
        return [
            'create' => CreateAction::className(),
            'read' => ReadAction::className(),
            'update' => UpdateAction::className(),
            'delete' => DeleteAction::className(),
            'list' => ListAction::className()
        ];
    }
}
