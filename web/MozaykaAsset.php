<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class MozaykaAsset extends AssetBundle
{

    public $depends = [
        'yii\mozayka\web\DropdownAsset',
        'yii\mozayka\web\IframeTransportAsset',
        'yii\mozayka\web\DatePickerAsset',
        'yii\mozayka\web\TimePickerAsset',
        'yii\mozayka\web\TinyMceAsset'
    ];
}
