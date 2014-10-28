<?php

namespace yii\mozayka;

use yii\web\AssetBundle,
    Yii;


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
        'yii\jui\JuiAsset',
        'yii\mozayka\DatePickerAsset'
    ];

    public function init()
    {
        $sourcePath = Yii::getAlias($this->sourcePath);
        $language = Yii::$app->language;
        $jsFile = 'i18n/jquery-ui-timepicker-' . $language . '.js';
        if (is_file($sourcePath . '/' . $jsFile)) {
            $this->js[] = $jsFile;
        } elseif (strlen($language) > 2) {
            $jsFile = 'i18n/jquery-ui-timepicker-' . substr($language, 0, 2) . '.js';
            if (is_file($sourcePath . '/' . $jsFile)) {
                $this->js[] = $jsFile;
            }
        }
        parent::init();
    }
}
