<?php

namespace yii\mozayka;

use yii\web\AssetBundle;


class TimePickerAsset extends AssetBundle
{

    public $sourcePath = '@bower/jqueryui-timepicker-addon/dist';

    public $css = [
        'jquery-ui-timepicker-addon.min.css'
    ];

    public $js = [
        'jquery-ui-timepicker-addon.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}
