<?php

namespace yii\mozayka\web;

use yii\web\UrlRule as YiiUrlRule,
    Yii;


class UrlRule extends YiiUrlRule
{

    public function createUrl($manager, $route, $params)
    {
        if (!array_key_exists('modelId', $params)) {
            $modelId = Yii::$app->getRequest()->getQueryParam('modelId');
            if ($modelId) {
                $params['modelId'] = $modelId;
            }
        }
        return parent::createUrl($manager, $route, $params);
    }
}
