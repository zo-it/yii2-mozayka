<?php

namespace yii\mozayka\web;

use yii\web\AssetBundle,
    Yii;


class DatePickerAsset extends AssetBundle
{

    public $sourcePath = '@bower/jquery-ui';

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];

    public function init()
    {
        $sourcePath = Yii::getAlias($this->sourcePath);
        $language = Yii::$app->language;
        $jsFile = 'ui/i18n/datepicker-' . $language . '.js';
        if (is_file($sourcePath . '/' . $jsFile)) {
            $this->js[] = $jsFile;
        } elseif (strlen($language) > 2) {
            $jsFile = 'ui/i18n/datepicker-' . substr($language, 0, 2) . '.js';
            if (is_file($sourcePath . '/' . $jsFile)) {
                $this->js[] = $jsFile;
            }
        }
        parent::init();
    }

    public static function register($view)
    {
        $view->registerJs('jQuery(\'<input>\').datepicker(\'widget\').on(\'click\', function (event) { event.stopPropagation(); });');
        return $view->registerAssetBundle(get_called_class());
    }
}
