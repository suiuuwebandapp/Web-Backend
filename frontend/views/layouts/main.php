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
<html lang="<?= Yii::$app->params['language']; ?>">
<head>
    <meta name="keywords" content="随游，随游网，出境游，出境旅游，出境自由行，海外旅行，订制旅行" />
    <meta name="description" content="随游是一个专注于海外目的地旅行体验的社区型服务平台，在这里人们既是导游也是旅行者，可以通过网站，微信，移动设备等各种方式发布，分享，预订全球的各种独特旅行体验及服务。无论您想体验不一样的异国文化，或和当地人结伴同行，甚至只是在旅行途中需要达人的帮助指点。您都能以任何价位享受到随游在全球多个城市为您带来的独一无二的旅行体验。随游相信用户提供的价值，我们不仅是旅行体验服务平台，更希望能够成为所有旅行者喜爱的旅行分享社区。" />
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->params['name'] ?> <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="/assets/js/jquery-1.10.2.min.js"></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "//hm.baidu.com/hm.js?7f949e4cb777fc26ff22fc86b0f20ee0";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
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
