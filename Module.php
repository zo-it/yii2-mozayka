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
            //$app->getUrlManager()->addRules([], false);
        }
    }
}
