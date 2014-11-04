<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView;


class GridView extends YiiGridView
{

    public $dataColumnClass = 'yii\mozayka\grid\DataColumn';

    public $filterFields = [];

    /**
     * @var yii\mozayka\form\ActiveForm the form that this grid is wrapped up.
     */
    public $form = null;
}
