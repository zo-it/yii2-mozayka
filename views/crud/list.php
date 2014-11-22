<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var yii\db\ActiveRecord|null $filterModel
 * @var array $filterFields
 * @var string $formClass
 * @var array $formConfig
 * @var string $gridClass
 * @var array $gridConfig
 * @var bool $canCreate
 */

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
//$buttons[] = Html::button(Yii::t('mozayka', 'Export'), ['class' => 'btn btn-default']);

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix hidden-print']);

/*echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', Yii::t('mozayka', 'Filter'), ['class' => 'panel-title']), ['class' => 'panel-heading']);
$form = $formClass::begin($formConfig);
echo Html::tag('div', $form->fields($filterModel, $filterFields), ['class' => 'panel-body']);
echo Html::tag('div', ButtonGroup::widget([
    'buttons' => [],
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);
$formClass::end();
echo Html::endTag('div');*/


echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title']), ['class' => 'panel-heading']);
//$form = $formClass::begin($formConfig);

echo Html::tag('div', /*$form->fields($model, $fields)*/'qwe', ['class' => 'panel-body']);

$buttons = [];
$buttons[] = Html::submitButton(Yii::t('mozayka', 'Save'), ['class' => 'btn btn-primary']);
/*if ($canList) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}*/

/*echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);*/

echo $gridClass::widget($gridConfig);

//$formClass::end();
echo Html::endTag('div');
