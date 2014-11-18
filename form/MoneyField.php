<?php

namespace yii\mozayka\form;


class MoneyField extends DecimalField
{

    public $size = 20;

    public $scale = 2;

    public $pluginOptions = ['digitsOptional' => true];
}
