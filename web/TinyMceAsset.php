<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class TinyMceAsset extends AssetBundle
{

    public $sourcePath = '@bower/tinymce';

    public $js = [
        'tinymce.jquery.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
