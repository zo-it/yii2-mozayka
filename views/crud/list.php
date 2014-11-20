<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord|null $filterModel
 * @var array $filterFields
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

/*$form = $formClass::begin($formConfig);
foreach ($filterFields as $attribute => $options) {
    echo $form->field($filterModel, $attribute, $options);
}
$formClass::end();*/

echo $gridClass::widget($gridConfig);
