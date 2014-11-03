<?php

namespace yii\mozayka\grid;

use yii\grid\GridView as YiiGridView;


class GridView extends YiiGridView
{

    public $dataColumnClass = 'yii\mozayka\grid\DataColumn';

    public $form = null;
}
