<?php
use yii\bootstrap\Alert,
    yii\bootstrap\ButtonGroup,
    yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 */

if ($successMessage) {
    echo Alert::widget(['body' => $successMessage, 'options' => ['class' => 'alert-success']]);
}

if ($errorMessage) {
    echo Alert::widget(['body' => $errorMessage, 'options' => ['class' => 'alert-danger']]);
}

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    echo $form->field($model, $attribute, $options);
}

echo Html::tag('div', ButtonGroup::widget([
    'buttons' => [
        Html::submitButton(Yii::t('mozayka', 'Save'), ['class' => 'btn btn-primary']),
        Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn btn-default'])
    ],
    'options' => ['class' => 'pull-right']
]), ['class' => 'clearfix']);

$formClass::end();
