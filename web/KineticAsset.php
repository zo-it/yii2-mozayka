<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class KineticAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery.kinetic';

    public $depends = ['yii\web\JqueryAsset'];

    public $js = ['jquery.kinetic.min.js'];
}
