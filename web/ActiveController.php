<?php

namespace yii\mozayka\web;


class ActiveController extends Controller
{

    public function actions()
    {
        return [
            'list' => 'yii\mozayka\crud\ListAction',
            'create' => 'yii\mozayka\crud\CreateAction',
            'read' => 'yii\mozayka\crud\ReadAction',
            'update' => 'yii\mozayka\crud\UpdateAction',
            'updatePosition' => 'yii\mozayka\crud\UpdatePositionAction',
            'delete' => 'yii\mozayka\crud\DeleteAction'
        ];
    }
}
