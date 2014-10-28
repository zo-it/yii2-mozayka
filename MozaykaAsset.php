<?php

namespace yii\mozayka;

use yii\web\AssetBundle;


class MozaykaAsset extends AssetBundle
{

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\mozayka\DatePickerAsset',
        'yii\mozayka\TimePickerAsset'
    ];
}
