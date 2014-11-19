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

MozaykaAsset::register($this);
$homeUrl = Yii::$app->getHomeUrl();
$navItems = array_merge([
    ['label' => Yii::t('mozayka', 'Home'), 'url' => $homeUrl]
], $this->params['navItems']);
$user = Yii::$app->getUser();
if ($user->getIsGuest()) {
    $navItems[] = ['label' => Yii::t('mozayka', 'Login'), 'url' => ['default/login-form']];
} else {
    $navItems[] = ['label' => Yii::t('mozayka', 'Logout') . ' (' . $user->getIdentity()->username . ')', 'url' => ['default/logout']];
}
$breadcrumbs = array_merge([
    ['label' => Yii::t('mozayka', 'Home'), 'url' => $homeUrl]
], $this->params['breadcrumbs']);
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
$navBar = NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => $homeUrl,
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
echo Breadcrumbs::widget([
    'homeLink' => false,
    'links' => $breadcrumbs
]);
echo $content;
?>
</div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?php echo Yii::$app->name; ?> <?php echo date('Y'); ?></p>
        <p class="pull-right"><?php echo Yii::powered(); ?></p>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php
$this->endPage();
