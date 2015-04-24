<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
</head>
<body style="background:#eeeeee;">
<?php $this->beginBody() ?>
<?php include 'top.php' ?>
<?= Alert::widget() ?>
<?= Html::beginForm() ?>
<?= Html::endForm() ?>
<?= $content ?>
<?php include 'bottom.php' ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
