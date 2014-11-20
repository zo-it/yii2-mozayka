<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class IframeTransportAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery.iframe-transport';

    public $depends = ['yii\web\JqueryAsset'];

    public $js = ['jquery.iframe-transport.js'];
}
