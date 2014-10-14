<?php

namespace yii\gii;

use yii\base\Module as ModuleBase,
    yii\base\BootstrapInterface,
    yii\base\Application;


class Module extends ModuleBase implements BootstrapInterface
{

    public function bootstrap(Application $app)
    {
        // nothing to do here
    }
}
