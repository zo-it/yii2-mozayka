<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var yii\db\ActiveRecord $model
 * @var string $id
 * @var string $caption
 * @var array $fields
 * @var string $formClass
 * @var array $formConfig
 * @var bool $canList
 * @var string $listLabel
 */

$this->title = Yii::t('mozayka', 'Updating record "{caption}"', ['caption' => $caption]);
if ($canList) {
    $this->params['breadcrumbs'][] = ['label' => $listLabel, 'url' => ['list']];
}
$this->params['breadcrumbs'][] = $caption;

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

echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title']), ['class' => 'panel-heading']);
$form = $formClass::begin($formConfig);

echo Html::tag('div', $form->fields($model, $fields), ['class' => 'panel-body']);

$buttons = [];
$buttons[] = Html::submitButton(Yii::t('mozayka', 'Save'), ['class' => 'btn btn-primary']);
if ($canList) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);

$formClass::end();
echo Html::endTag('div');
