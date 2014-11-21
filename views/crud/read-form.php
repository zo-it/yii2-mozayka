<?php
use yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var yii\db\ActiveRecord $model
 * @var string $id
 * @var string $caption
 * @var array $fields
 * @var string $formClass
 * @var array $formConfig
 * @var bool $canList
 * @var string $listLabel
 */

$this->title = Yii::t('mozayka', 'Record "{record}"', ['record' => $caption]);
if ($canList) {
    $this->params['breadcrumbs'][] = ['label' => $listLabel, 'url' => ['list']];
}
$this->params['breadcrumbs'][] = $caption;

echo Html::beginTag('div', ['class' => 'panel panel-default']);
echo Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title']), ['class' => 'panel-heading']);
$form = $formClass::begin($formConfig);

echo Html::tag('div', $form->fields($model, $fields), ['class' => 'panel-body']);

$buttons = [];
$buttons[] = Html::button(Yii::t('mozayka', 'Print'), [
    'class' => 'btn btn-default',
    'onclick' => 'print();'
]);
if ($canList) {
    $buttons[] = Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default']);
}

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => $buttons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);

$formClass::end();
echo Html::endTag('div');
