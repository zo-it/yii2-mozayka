<?php

namespace yii\mozayka;

use yii\base\Module as YiiModule,
    yii\base\BootstrapInterface,
    yii\web\Application as YiiWebApplication;


class Module extends YiiModule implements BootstrapInterface
{

    public function bootstrap($app)
    {
        if ($app instanceof YiiWebApplication) {
            $app->getUrlManager()->addRules([
                $this->id => $this->id . '/default',
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
            $app->getI18n()->translations['mozayka'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@yii/mozayka/messages'
            ];
        }
    }
}
