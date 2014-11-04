<?php
use yii\bootstrap\ButtonGroup,
    yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 */

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    echo $form->field($model, $attribute, $options);
}

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => [
        Html::button(Yii::t('mozayka', 'Print'), ['class' => 'btn btn-default', 'onclick' => 'print();']),
        Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default'])
    ],
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix']);

$formClass::end();
