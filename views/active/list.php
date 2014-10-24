<?php
use yii\bootstrap\Alert,
    yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var string $gridClass
 * @var array $gridConfig
 */

if ($successMessage) {
    echo Alert::widget(['body' => $successMessage, 'options' => ['class' => 'alert-success']]);
}

if ($errorMessage) {
    echo Alert::widget(['body' => $errorMessage, 'options' => ['class' => 'alert-danger']]);
}

echo $gridClass::widget($gridConfig);

echo Html::a(Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary']);
