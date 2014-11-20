<?php
use yii\helpers\Html,
    yii\bootstrap\ButtonGroup;
/**
 * @var yii\web\View $this
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 * @var bool $canList
 */

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    echo $form->field($model, $attribute, $options);
}

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
]), ['class' => 'clearfix hidden-print']);

$formClass::end();
