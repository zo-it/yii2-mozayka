<?php
use yii\mozayka\MozaykaAsset,
    yii\helpers\Html,
    yii\bootstrap\NavBar,
    yii\bootstrap\Nav,
    yii\widgets\Breadcrumbs;
/**
 * @var yii\web\View $this
 * @var string $content
 */

$navItems = [
    ['label' => Yii::t('mozayka', 'Home'), 'url' => ['default/index']]
];
$user = Yii::$app->getUser();
if ($user->getIsGuest()) {
    $navItems[] = ['label' => Yii::t('mozayka', 'Login'), 'url' => ['default/login-form']];
} else {
    $navItems[] = ['label' => Yii::t('mozayka', 'Logout') . ' (' . $user->getIdentity()->username . ')', 'url' => ['default/logout']];
}

MozaykaAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo Html::csrfMetaTags(); ?>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body>

<?php $this->beginBody(); ?>
<div class="wrap">
<?php
NavBar::begin([
    'brandLabel' => 'My Company',
    'brandUrl' => Yii::$app->getHomeUrl(),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top'
    ]
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $navItems
]);
NavBar::end();
?>

<div class="container">
<?php
if (array_key_exists('breadcrumbs', $this->params)) {
    echo Breadcrumbs::widget([
        'links' => $this->params['breadcrumbs']
    ]);
}
echo $content;
?>
</div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?php echo date('Y'); ?></p>
        <p class="pull-right"><?php echo Yii::powered(); ?></p>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php
$this->endPage();
