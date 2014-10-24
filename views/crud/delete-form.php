<?php
use yii\bootstrap\Alert,
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

echo Html::submitButton(Yii::t('mozayka', 'Delete'), ['class' => 'btn btn-danger']);

echo Html::a(Yii::t('mozayka', 'Back'), ['list'], ['class' => 'btn']);

$formClass::end();
