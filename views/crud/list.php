<?php
use yii\bootstrap\Alert,
    yii\bootstrap\ButtonGroup,
    yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var string $gridClass
 * @var array $gridConfig
 * @var bool $canCreate
 */

if ($successMessage) {
    echo Alert::widget(['body' => $successMessage, 'options' => ['class' => 'alert-success']]);
}

if ($errorMessage) {
    echo Alert::widget(['body' => $errorMessage, 'options' => ['class' => 'alert-danger']]);
}

$buttonGroup = Html::tag('div', ButtonGroup::widget([
    'buttons' => [
        Html::a(Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary'])
    ],
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix']);
if ($canCreate) {
    echo $buttonGroup;
}

echo $gridClass::widget($gridConfig);

if ($canCreate) {
    echo $buttonGroup;
}
