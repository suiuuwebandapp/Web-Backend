<?php
use backend\assets\LoginAsset;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/1
 * Time : 下午1:46
 * Email: zhangxinmailvip@foxmail.com
 */

/* @var $this \yii\web\View */
/* @var $content string */

LoginAsset::register($this);
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
    <title><?= Yii::$app->params['name'] ?> | 登录 </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <meta name="MobileOptimized" content="320">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="/assets/img/favicon.ico"/>
    <link href="/assets/admin/pages/css/login-soft.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        if (window != top) {
            top.location.href = location.href;
        }
    </script>
</head>

<?php $this->beginBody() ?>
<?= Html::beginForm() ?>
<?= Html::endForm() ?>
<body class="login">
    <?= $content ?>
    <div class="copyright">
        <?= Yii::$app->params['copyright']?>
    </div>
<?php $this->endBody() ?>
</body>
<script type="text/javascript">
    var basePath = "/";
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets/plugins/respond.min.js"></script>
<script type="text/javascript" src="/assets/plugins/excanvas.min.js"></script>
<![endif]-->

<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/backstretch/jquery.backstretch.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/admin/pages/scripts/login-soft.js"></script>
<script>
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        Login.init();
        $.backstretch([
                "/assets/admin/pages/media/bg/1.jpg",
                "/assets/admin/pages/media/bg/2.jpg",
                "/assets/admin/pages/media/bg/3.jpg",
                "/assets/admin/pages/media/bg/4.jpg"
            ], {
                fade: 1000,
                duration: 8000
            }
        );
    });
</script>
</html>
</html>
<?php $this->endPage() ?>



