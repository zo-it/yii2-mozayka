<?php

namespace yii\mozayka;

use yii\web\AssetBundle;


class MozaykaAsset extends AssetBundle
{

    public $sourcePath = '@yii/mozayka/assets';

    public $depends = [
        'yii\widgets\MaskedInputAsset', // yii\web\YiiAsset
        'yii\bootstrap\BootstrapPluginAsset', // yii\bootstrap\BootstrapAsset
        'yii\mozayka\web\DropdownAsset',
        'yii\mozayka\web\IframeTransportAsset',
        'yii\mozayka\web\KineticAsset',
        'yii\mozayka\web\DatePickerAsset',
        'yii\mozayka\web\TimePickerAsset',
        'yii\mozayka\web\TinymceAsset'
    ];

    public $js = ['main.js'];

    public $css = ['main.css'];
}
