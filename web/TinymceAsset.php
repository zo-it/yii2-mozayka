<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle,
    Yii;


class TinymceAsset extends AssetBundle
{

    public $depends = ['yii\web\JqueryAsset'];

    public $sourcePath = '@bower/tinymce';

    public $js = ['tinymce.min.js', 'jquery.tinymce.min.js'];

    public function init()
    {
        $sourcePath = Yii::getAlias($this->sourcePath);
        $language = Yii::$app->language;
        $jsFile = 'langs/' . str_replace('-', '_', $language) . '.js';
        if (is_file($sourcePath . DIRECTORY_SEPARATOR . $jsFile)) {
            $this->js[] = $jsFile;
        } elseif (strlen($language) > 2) {
            $jsFile = 'langs/' . substr($language, 0, 2) . '.js';
            if (is_file($sourcePath . DIRECTORY_SEPARATOR . $jsFile)) {
                $this->js[] = $jsFile;
            }
        }
        parent::init();
    }
}
