<?php
use yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var string $gridClass
 * @var array $gridConfig
 */

echo $gridClass::widget($gridConfig);

echo Html::a(Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary']);
