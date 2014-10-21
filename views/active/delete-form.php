<?php
/**
 * @var string $formClass
 * @var array $formConfig
 * @var yii\db\ActiveRecord $model
 * @var array $fields
 */

$form = $formClass::begin($formConfig);

foreach ($fields as $attribute => $options) {
    $options['readonly'] = true;
    echo $form->field($model, $attribute, $options);
}

$formClass::end();
