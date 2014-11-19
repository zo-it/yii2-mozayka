<?php

namespace yii\mozayka;

use yii\web\AssetBundle;


class MozaykaAsset extends AssetBundle
{

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\mozayka\web\DropdownAsset',
        'yii\mozayka\web\IframeTransportAsset',
        'yii\mozayka\web\DatePickerAsset',
        'yii\mozayka\web\TimePickerAsset',
        'yii\mozayka\web\TinymceAsset'
    ];

    public $sourcePath = '@yii/mozayka/assets';

    public $css = ['main.css'];
}
