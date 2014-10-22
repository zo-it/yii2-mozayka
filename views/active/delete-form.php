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
    $options['readOnly'] = true;
    echo $form->field($model, $attribute, $options);
}

echo Html::submitButton('Delete', ['class' => 'btn btn-danger']);

echo Html::button('Back', ['class' => 'btn']);

$formClass::end();
