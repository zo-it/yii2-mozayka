<?php
use yii\helpers\Html;
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

echo Html::submitButton(Yii::t('mozayka', 'Delete'), ['class' => 'btn btn-danger']);

echo Html::a(Yii::t('mozayka', 'Back'), [Yii::$app->controller->id . '/list'], ['class' => 'btn']);

$formClass::end();
