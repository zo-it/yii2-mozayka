<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class DropdownAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-dropdown';

    public $css = [
        'jquery.dropdown.css'
    ];

    public $js = [
        'jquery.dropdown.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
