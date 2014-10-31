<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class TinyMceAsset extends AssetBundle
{

    public $sourcePath = '@bower/tinymce';

    public $js = [
        'tinymce.min.js',
        'jquery.tinymce.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
