<?php

namespace yii\mozayka\helpers;

use yii\kladovka\helpers\BaseHtml as KladovkaBaseHtml;


class BaseHtml extends KladovkaBaseHtml
{

    public static $inputIdSuffix = '';

    public static function getInputId($model, $attribute)
    {
        return parent::getInputId($model, $attribute) . static::$inputIdSuffix;
    }
}
