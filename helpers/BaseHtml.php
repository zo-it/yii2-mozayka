<?php

namespace yii\mozayka\helpers;

use yii\kladovka\helpers\BaseHtml as KladovkaBaseHtml;


class BaseHtml extends KladovkaBaseHtml
{

    public static $inputIdPrefix = '';

    public static $inputIdSuffix = '';

    public static function getInputId($model, $attribute)
    {
        return static::$inputIdPrefix . parent::getInputId($model, $attribute) . static::$inputIdSuffix;
    }
}
