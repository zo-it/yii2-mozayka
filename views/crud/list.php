<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var bool $canCreate
 * @var string $pluralHumanName
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var yii\db\ActiveRecord|null $filterModel
 * @var array $filterFields
 * @var string $formClass
 * @var array $formConfig
 * @var string $gridClass
 * @var array $gridConfig
 */

$this->title = Yii::t('mozayka', 'Record list "{list}"', ['list' => $pluralHumanName]);
$this->params['breadcrumbs'][] = $pluralHumanName;

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
if ($canCreate) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary']);
}
$buttons[] = Html::button(Yii::t('mozayka', 'Print'), [
    'class' => 'btn btn-default',
    'onclick' => 'print();'
]);
$buttons[] = Html::button(Yii::t('mozayka', 'Filter'), [
    'class' => 'btn btn-default',
    'onclick' => 'jQuery(this).toggleClass(\'active\'); jQuery(\'.panel-body\').slideToggle();'
]);

echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title pull-left']) . ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right hidden-print']
]), ['class' => 'panel-heading clearfix']);

$form = $formClass::begin($formConfig);
echo Html::tag('div', $form->fields($filterModel, $filterFields), [
    'class' => 'panel-body',
    'style' => 'display: none;'
]);
$formClass::end();

echo $gridClass::widget($gridConfig);

echo Html::endTag('div');
