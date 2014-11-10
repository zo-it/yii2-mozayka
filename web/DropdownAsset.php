<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle,
    yii\web\View;


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

    public static function register($view)
    {
        $view->registerJs('jQuery(document).on(\'beforeFilter.yiiGridView\', function (event) { if (jQuery(\'.dropdown\').is(\':visible\')) event.result = false; });');
        $view->registerJs('jQuery.fn.dropdown2 = jQuery.fn.dropdown;', View::POS_HEAD);
        return $view->registerAssetBundle(get_called_class(), View::POS_HEAD);
    }
}
