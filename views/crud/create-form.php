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
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 * @var bool $canList
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

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    echo $form->field($model, $attribute, $options);
}

$buttons = [];
$buttons[] = Html::submitButton(Yii::t('mozayka', 'Save'), ['class' => 'btn btn-primary']);
if ($canList) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix hidden-print']);

$formClass::end();
