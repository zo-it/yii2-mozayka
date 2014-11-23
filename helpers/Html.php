<?php

namespace yii\mozayka\helpers;


class Html extends BaseHtml
{
}

if (!class_exists('yii\helpers\Html', false)) {
    class_alias('yii\mozayka\helpers\Html', 'yii\helpers\Html', false);
}
