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
                $this->id . '/ext-js' => $this->id . '/ext-js/index',
                $this->id . '/Application.js' => $this->id . '/ext-js/application-js'
            ], false);
        }
    }
}
