<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class IframeTransportAsset extends AssetBundle
{

    public $depends = ['yii\web\JqueryAsset'];

    public $sourcePath = '@bower/jquery.iframe-transport';

    public $js = ['jquery.iframe-transport.js'];
}
