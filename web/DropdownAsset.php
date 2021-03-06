<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class DropdownAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-dropdown';

    public $depends = ['yii\web\JqueryAsset'];

    public $js = ['jquery.dropdown.min.js'];

    public $css = ['jquery.dropdown.css'];

    public function init()
    {
        $this->publishOptions['afterCopy'] = function ($from, $to) {
            if (in_array(basename($to), ['jquery.dropdown.min.js', 'jquery.dropdown.js', 'jquery.dropdown.css'])) {
                file_put_contents($to, str_replace('dropdown', 'dropdown2', file_get_contents($to)));
            }
        };
        parent::init();
    }

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $view->registerJs('jQuery(document).on(\'beforeFilter.yiiGridView\', function (event) { if (jQuery(\'.dropdown2\').is(\':visible\')) event.result = false; });');
    }
}
