<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class PepAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery.pep/src';

    public $depends = ['yii\web\JqueryAsset'];

    public $js = ['jquery.pep.js'];
}
