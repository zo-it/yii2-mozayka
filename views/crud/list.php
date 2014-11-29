<?php
use yii\bootstrap\Alert,
    yii\helpers\Html,
    yii\bootstrap\ButtonGroup,
    yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var bool $canCreate
 * @var string $pluralHumanName
 * @var string|null $successMessage
 * @var string|null $errorMessage
 * @var yii\db\ActiveRecord|null $filterModel
 * @var array $filterFields
 * @var string $formClass
 * @var array $formConfig
 * @var string $gridClass
 * @var array $gridConfig
 */

$this->title = Yii::t('mozayka', 'Record list "{list}".', ['list' => $pluralHumanName]);
$this->params['breadcrumbs'][] = $pluralHumanName;

if ($successMessage) {
    echo Alert::widget([
        'body' => $successMessage,
        'options' => ['class' => 'alert-success hidden-print']
    ]);
}

if ($errorMessage) {
    echo Alert::widget([
        'body' => $errorMessage,
        'options' => ['class' => 'alert-danger hidden-print']
    ]);
}

$buttons = [];
if ($canCreate) {
    $buttons[] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('mozayka', 'Create'), ['create-form'], ['class' => 'btn btn-primary']);
}
$buttons[] = Html::button('<span class="glyphicon glyphicon-print"></span> ' . Yii::t('mozayka', 'Print'), [
    'class' => 'btn btn-default',
    'onclick' => 'print();'
]);

$headerButtons = $buttons;
$footerButtons = $buttons;

echo Html::beginTag('div', ['class' => 'panel panel-default']);

if ($filterModel && $filterFields) {
    $headerButtons[] = Html::button('<span class="glyphicon glyphicon-filter"></span> ' . Yii::t('mozayka', 'Filter') . ' <span class="caret"></span>', [
        'class' => 'btn btn-default',
        'onclick' => 'jQuery(this).toggleClass(\'active\').closest(\'.panel\').children(\'.panel-body\').slideToggle();'
    ]);
}

echo Html::tag('div', Html::tag('div', '&nbsp;', ['class' => 'external-grid-pager pull-left']) . Html::tag('div', Html::tag('h3', $this->title, ['class' => 'panel-title']) . Html::tag('div', '&nbsp;', ['class' => 'external-grid-summary']), ['class' => 'pull-left']) . ButtonGroup::widget([
    'buttons' => $headerButtons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-heading clearfix hidden-print']);

if ($filterModel && $filterFields) {
    echo Html::beginTag('div', [
        'class' => 'panel-body hidden-print',
        'style' => 'display: none;'
    ]);
    $form = $formClass::begin($formConfig);
    $form->inputIdSuffix = '-ex'; // no repeated ids
    $filterButtons = [
        Html::submitButton('<span class="glyphicon glyphicon-search"></span> ' . Yii::t('mozayka', 'Apply'), ['class' => 'btn btn-primary']),
        Html::button('<span class="glyphicon glyphicon-ban-circle"></span> ' . Yii::t('mozayka', 'Reset'), [
            'class' => 'btn btn-default',
            'onclick' => 'jQuery(\'#' . $form->getId() . '\').find(\'input[type="text"], input[type="hidden"], textarea, select\').val(\'\');'
        ])
    ];
    echo Html::tag('div', ButtonGroup::widget([
        'buttons' => $filterButtons,
        'options' => ['class' => 'pull-right']
    ]), ['class' => 'clearfix']);
    echo $form->fields($filterModel, $filterFields);
    echo Html::tag('div', ButtonGroup::widget([
        'buttons' => $filterButtons,
        'options' => ['class' => 'pull-right']
    ]), ['class' => 'clearfix']);
    $formClass::end();
    echo Html::endTag('div'); // panel-body
}

Pjax::begin(['linkSelector' => '.external-grid-pager a, .grid-view a.sort-link']);

$grid = $gridClass::begin($gridConfig);
$gridPager = $grid->renderPager();
$gridSummary = $grid->renderSummary();
$grid->layout = '{items}';
$gridClass::end();

$js = 'jQuery(\'#' . $grid->getId() . '\').closest(\'.panel\').find(\'.external-grid-pager\').html(\'' . preg_replace('~([\r\n]+)~', '\'+$1\'', addslashes($gridPager)) . '\');';
$js .= 'jQuery(\'#' . $grid->getId() . '\').closest(\'.panel\').find(\'.external-grid-summary\').html(\'' . preg_replace('~([\r\n]+)~', '\'+$1\'', addslashes($gridSummary)) . '\');';
if (Yii::$app->getRequest()->getIsAjax()) {
    $js .= 'document.title = \'' . $this->title . ' ' . strip_tags($gridSummary) . '\';';
    echo Html::script($js);
} else {
    $this->title .= ' ' . strip_tags($gridSummary);
    $this->registerJs($js);
}

Pjax::end();

$footerButtons[] = Html::button('<span class="glyphicon glyphicon-arrow-up"></span> ' . Yii::t('mozayka', 'Up'), [
    'class' => 'btn btn-default',
    'onclick' => 'jQuery(document).scrollTop(0);'
]);

echo Html::tag('div', Html::tag('div', '&nbsp;', ['class' => 'external-grid-pager pull-left']) . ButtonGroup::widget([
    'buttons' => $footerButtons,
    'options' => ['class' => 'pull-right']
]), ['class' => 'panel-footer clearfix hidden-print']);

echo Html::endTag('div'); // panel
