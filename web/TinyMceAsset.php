<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle,
    Yii;


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

    public function init()
    {
        $sourcePath = Yii::getAlias($this->sourcePath);
        $language = Yii::$app->language;
        $jsFile = 'langs/' . str_replace('-', '_', $language) . '.js';
        if (is_file($sourcePath . '/' . $jsFile)) {
            $this->js[] = $jsFile;
        } elseif (strlen($language) > 2) {
            $jsFile = 'langs/' . substr($language, 0, 2) . '.js';
            if (is_file($sourcePath . '/' . $jsFile)) {
                $this->js[] = $jsFile;
            }
        }
        parent::init();
    }
}
