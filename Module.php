<?php

namespace yii\mozayka;

use yii\base\Module as BaseModule,
    yii\base\BootstrapInterface,
    yii\web\Application as WebApplication;


class Module extends BaseModule implements BootstrapInterface
{

    public function bootstrap($app)
    {
        if ($app instanceof WebApplication) {
            $app->getUrlManager()->addRules([
                $this->id . '/ext-js' => $this->id . '/ext-js/index',
                $this->id . '/Application.js' => $this->id . '/ext-js/application-js'
            ], false);
        }
    }
}
