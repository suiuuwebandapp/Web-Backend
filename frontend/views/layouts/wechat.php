<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
$userObj = $this->context->userObj;
$active =  $this->context->activeIndex;
$newMsg=$this->context->unReadMessageList;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->params['language']; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <?= Html::csrfMetaTags() ?>
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>

    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
    <style>
        .banners li img{
            min-height:10.89rem;
        }
    </style>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">
</head>
<body  class="<?php if($this->context->bgWhite){echo "bgwhite";};?>" onload="showHtml()">


<?php $this->beginBody() ?>
<?= Html::beginForm() ?>
<?= Html::endForm() ?>
<script>
    var sessionId = '<?=session_id()?>';
    function newMessage(messageInfo)
    {
        $("#left_msg").attr("class","tip");
    }
</script>
<?php if(!empty($userObj)&&!empty($userObj->userSign)){
    ?>
    <script type="text/javascript" src="/assets/other/weixin/js/ws.js"></script>
<?php }?>
<?php if(!isset($userObj->userSign)){
    $userObj=new \common\entity\UserBase();
    $active=7;
    $newMsg=null;
}
?>
<div id="loading" class="overlay" style="z-index: 99999">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page"  class="userCenter">
<div id="menu" class="navBar">
    <ul>
        <li class="user">
            <a href="<?= empty($userObj->userSign)?"javascript:;":"/wechat-user-info/user-info?userSign=$userObj->userSign" ?>" class="userPic"><img src="<?= $userObj->headImg?$userObj->headImg:'/assets/other/weixin/images/logo02.png'?>"></a>
            <span class="userName"><?= $userObj->nickname?></span>
        </li>
        <li><a href="/wechat-trip" <?php if($active==1){echo 'class="active"';}?>>首页</a></li>
        <li><a href="/we-chat-order-list/order-manage"<?php if($active==2){echo 'class="active"';}?> >我的定制</a></li>
        <li><a href="/wechat-user-center/my-order" <?php if($active==3){echo 'class="active"';}?> >我的订单</a></li>
        <li ><a  href="/wechat-user-center/get-user-remind" <?php if($active==4){echo 'class="active"';}?> ><b id="left_msg" <?php if(!empty($newMsg)){echo 'class="tip"';}?>></b> 我的消息</a></li>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/my-trip" <?php if($active==5){echo 'class="active"';}?>>我的随游</a></li>
        <?php }?>
        <?php if($userObj->isPublisher==1){?>
            <li><a href="/wechat-user-center/trip-order" <?php if($active==6){echo 'class="active"';}?> >随游订单</a></li>
        <?php }?>
        <?php if(empty($userObj->userSign)){?>
            <li><a href="/we-chat/login" <?php if($active==7){echo 'class="active"';}?>>登录</a></li>
        <?php }else{?>
            <li><a href="/wechat-user-info/setting" <?php if($active==8){echo 'class="active"';}?>>设置</a></li>
            <li><a href="/we-chat/logout">退出</a></li>
        <?php }?>
    </ul>
</div>
<?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>