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
if ($filterModel && $filterFields) {
    $buttons[] = Html::button(Yii::t('mozayka', 'Filter'), [
        'class' => 'btn btn-default',
        'onclick' => 'jQuery(this).toggleClass(\'active\').closest(\'.panel\').children(\'.panel-body\').slideToggle();'
    ]);
}

echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title pull-left']) . ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right hidden-print']
]), ['class' => 'panel-heading clearfix']);

if ($filterModel && $filterFields) {
    echo Html::beginTag('div', [
        'class' => 'panel-body hidden-print',
        'style' => 'display: none;'
    ]);
    $form = $formClass::begin($formConfig);
    $form->inputIdSuffix = '-2'; // no repeated ids
    echo $form->fields($filterModel, $filterFields);
    echo Html::tag('div', ButtonGroup::widget([
        'buttons' => [
            Html::submitButton(Yii::t('mozayka', 'Apply'), ['class' => 'btn btn-primary']),
            Html::button(Yii::t('mozayka', 'Clear'), [
                'class' => 'btn btn-default',
                'onclick' => 'jQuery(\'#' . $form->getId() . '\').find(\'input[type="text"], input[type="hidden"], textarea, select\').val(\'\');'
            ])
        ],
        'options' => ['class' => 'pull-right']
    ]), ['class' => 'clearfix']);
    $formClass::end();
    echo Html::endTag('div');
}

echo $gridClass::widget($gridConfig);

echo Html::endTag('div');
