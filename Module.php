<?php

namespace yii\mozayka;

use yii\base\Module as YiiModule,
    yii\base\BootstrapInterface,
    yii\web\Application as YiiWebApplication;


class Module extends YiiModule implements BootstrapInterface
{

    public $layout = 'main';

    public $appName = 'Mozayka';

    public $navItems = [];

    public function bootstrap($app)
    {
        if ($app instanceof YiiWebApplication) {
            $app->getUrlManager()->addRules([
                $this->id => $this->id . '/default/index',
                $this->id . '/login-form' => $this->id . '/default/login-form',
                $this->id . '/logout' => $this->id . '/default/logout',
                [
                    'class' => 'yii\mozayka\web\UrlRule',
                    'pattern' => $this->id . '/<modelId:[\w\-]+>',
                    'route' => $this->id . '/crud/list'
                ],
                [
                    'class' => 'yii\mozayka\web\UrlRule',
                    'pattern' => $this->id . '/<modelId:[\w\-]+>/<action:[\w\-]+>',
                    'route' => $this->id . '/crud/<action>'
                ]
            ]);
            $app->setHomeUrl(['default/index']);
            $app->getI18n()->translations['mozayka'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@yii/mozayka/messages'
            ];
        }
    }
}
