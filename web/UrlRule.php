<?php

namespace yii\mozayka\web;

use yii\web\UrlRule as YiiUrlRule,
    Yii;


class UrlRule extends YiiUrlRule
{

    public function createUrl($manager, $route, $params)
    {
        $request = Yii::$app->getRequest();
        if (!array_key_exists('modelId', $params)) {
            $modelId = $request->getQueryParam('modelId');
            if ($modelId) {
                $params['modelId'] = $modelId;
            }
        }
if (strncmp($route, 'mozayka/crud/', 13) == 0) {
    $controller = Yii::$app->controller;
    if ($controller->id == 'crud') {
        if ($controller->action->id == 'list') {
            if ($route != 'mozayka/crud/list') {
                $params['uniqId'] = uniqid();
            }
        } else {
            $uniqId = $request->getQueryParam('uniqId');
            if ($uniqId) {
                $session = Yii::$app->getSession();
                if (!$session->has($uniqId)) {
                    $session->set($uniqId, $request->getReferrer());
                }
                if ($route != 'mozayka/crud/list') {
                    $params['uniqId'] = $uniqId;
                } else {
                    $referrer = $session->get($uniqId);
                    if ($referrer) {
                        return $referrer;
                    }
                }
            }
        }
    }
}
        return parent::createUrl($manager, $route, $params);
    }
}
