<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle;


class DropdownAsset extends AssetBundle
{

    public $depends = ['yii\web\JqueryAsset'];

    public $sourcePath = '@bower/jquery-dropdown';

    public $css = ['jquery.dropdown.css'];

    public $js = ['jquery.dropdown.min.js'];

    public function init()
    {
        $this->publishOptions['afterCopy'] = function ($from, $to) {
            if (in_array(basename($to), ['jquery.dropdown.css', 'jquery.dropdown.js', 'jquery.dropdown.min.js'])) {
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
