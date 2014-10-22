<?php
use yii\helpers\Html;
/**
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 */

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    echo $form->field($model, $attribute, $options);
}

echo Html::submitButton('Save', ['class' => 'btn btn-primary']);

echo Html::button('Back', ['class' => 'btn']);

$formClass::end();
