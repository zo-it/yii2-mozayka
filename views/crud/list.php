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

$buttons = [];
if ($canCreate) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary']);
}
$buttons[] = Html::button(Yii::t('mozayka', 'Print'), ['class' => 'btn btn-default', 'onclick' => 'print();']);

$buttonGroup = Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix']);
echo $buttonGroup;

echo $gridClass::widget($gridConfig);

echo $buttonGroup;
