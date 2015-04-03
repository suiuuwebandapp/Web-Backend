<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]>
<html lang="<?= Yii::$app->params['language']; ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="<?= Yii::$app->params['language']; ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->params['language']; ?>" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="MobileOptimized" content="320">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="/assets/img/favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content">
<?php $this->beginBody() ?>
<?php include 'top.php' ?>
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <?php include 'left.php' ?>
    <div class="page-content-wrapper">
        <!-- BEGIN PAGE -->
        <div class="page-content page-right">
            <?php include 'tools.php' ?>
            <!--                <iframe id="rightFrame" name="rightFrame" width="100%" src="../..${selectModule.url}?${params}" frameborder="0" style="min-height: 700px;"></iframe>-->
           <?= $content ?>
        </div>
        <!-- END PAGE -->
    </div>
</div>
<?php include 'bottom.php' ?>
</body>
<?php $this->endBody() ?>
<script type="text/javascript">
    var basePath = "/";
</script>
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/assets/plugins/respond.min.js"></script>
<script src="/assets/plugins/excanvas.min.js"></script>
<![endif]-->

<script>
    jQuery(document).ready(function () {
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        Index.init();
        Main.init({basePath: "/"});
    });
</script>
</html>
<?php $this->endPage() ?>