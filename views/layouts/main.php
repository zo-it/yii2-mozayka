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

$appName = Yii::$app->name;
$homeUrl = Yii::$app->getHomeUrl();

$navItems = [['label' => Yii::t('mozayka', 'Home'), 'url' => $homeUrl]];
if (array_key_exists('navItems', $this->params)) {
    $navItems = array_merge($navItems, $this->params['navItems']);
} else {
    $mozayka = Yii::$app->getModule('mozayka');
    if ($mozayka) {
        $navItems = array_merge($navItems, $mozayka->navItems);
    }
}

$user = Yii::$app->getUser();
if ($user->getIsGuest()) {
    $navItems[] = ['label' => Yii::t('mozayka', 'Login'), 'url' => ['default/login-form']];
} else {
    $navItems[] = ['label' => Yii::t('mozayka', 'Logout') . ' (' . $user->getIdentity()->username . ')', 'url' => ['default/logout']];
}

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
    'brandLabel' => $appName,
    'brandUrl' => $homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top hidden-print'
    ]
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $navItems
]);
NavBar::end();
?>
<div class="container-fluid">
<?php
if (array_key_exists('breadcrumbs', $this->params)) {
    echo Breadcrumbs::widget([
        'options' => ['class' => 'breadcrumb hidden-print'],
        'homeLink' => ['label' => Yii::t('mozayka', 'Home'), 'url' => $homeUrl],
        'links' => $this->params['breadcrumbs']
    ]);
}
echo $content;
?>
</div>
</div>

<footer class="footer hidden-print">
    <div class="container">
        <p class="pull-left">&copy; <?php echo $appName . date(' Y'); ?></p>
        <p class="pull-right"><?php echo Yii::powered(); ?></p>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php
$this->endPage();
