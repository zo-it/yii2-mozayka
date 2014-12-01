<?php
use yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var bool $canList
 * @var string $pluralHumanName
 * @var yii\db\ActiveRecord $model
 * @var string $id
 * @var string $displayField
 * @var array $fields
 * @var string $formClass
 * @var array $formConfig
 */

$this->title = Yii::t('mozayka', 'Record "{record}".', ['record' => $displayField]);
if ($canList) {
    $this->params['breadcrumbs'][] = ['label' => $pluralHumanName, 'url' => ['list']];
}
$this->params['breadcrumbs'][] = $displayField;

$buttons = [];
$buttons[] = Html::button('<span class="glyphicon glyphicon-print"></span> ' . Yii::t('mozayka', 'Print'), [
    'class' => 'btn btn-default',
    'onclick' => 'print();'
]);
if ($canList) {
    $buttons[] = Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}

$form = $formClass::begin($formConfig);
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
