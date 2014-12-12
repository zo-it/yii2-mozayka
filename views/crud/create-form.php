<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var bool $canList
 * @var string $pluralHumanName
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 * @var string $formClass
 * @var array $formConfig
 */

$this->title = Yii::t('mozayka', 'Creating new record.');
if ($canList) {
    $this->params['breadcrumbs'][] = ['label' => $pluralHumanName, 'url' => ['list']];
}
$this->params['breadcrumbs'][] = Yii::t('mozayka', 'New record');

if ($successMessage) {
    echo Alert::widget([
        'body' => $successMessage,
        'options' => ['class' => 'alert-success hidden-print']
    ]);
}

if ($errorMessage) {
    echo Alert::widget([
        'body' => $errorMessage,
        'options' => ['class' => 'alert-danger hidden-print']
    ]);
}

$buttons = [];
$buttons[] = Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . Yii::t('mozayka', 'Save'), ['class' => 'btn btn-primary']);
if ($canList) {
    $buttons[] = Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}

$form = $formClass::begin($formConfig);
$form->inputIdPrefix = $form->getId() . '-';
echo Html::beginTag('div', ['class' => 'panel panel-default']);

echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title pull-left']) . ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-heading clearfix hidden-print']);

echo Html::tag('div', $form->fields($model, $fields), ['class' => 'panel-body']);

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);

echo Html::endTag('div'); // panel
$formClass::end();
