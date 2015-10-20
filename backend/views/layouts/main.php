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
    <link rel="shortcut icon" href="favicon.ico"/>
    <!-- 将Jquery 引入到前方，方便内部引用-->
    <script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-1.11.0.min.js"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content">
<?php $this->beginBody() ?>
<?php include 'top.php' ?>
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<?= Html::beginForm() ?>
<?= Html::endForm() ?>
<div class="page-container">
    <?php include 'left.php' ?>
    <div class="page-content-wrapper">
        <!-- BEGIN PAGE -->
        <div class="page-content page-right">
            <?php include 'tools.php' ?>
            <?php include 'head.php' ?>
            <div id="div_main_container">
                <?= $content ?>
            </div>
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
<script src="<?=Yii::$app->params['res_url'] ?>/assets/plugins/respond.min.js"></script>
<script src="<?=Yii::$app->params['res_url'] ?>/assets/plugins/excanvas.min.js"></script>
<script type="text/javascript" src="/assets/chat/js/swfobject.js"></script>
<script type="text/javascript">WEB_SOCKET_SWF_LOCATION = "/assets/chat/swf/WebSocketMain.swf";</script>
<![endif]-->

<script type="text/javascript">
    /*** scoket connec***/
    if (typeof console == "undefined") {
        this.console = {log: function (msg) {}};
    }
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list = {}, timeid, reconnect = false;
    var sessionId = '<?=session_id()?>';

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