<?php
$wechat=false;
$sina=false;
$qq=false;
foreach($access as $val){
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_WECHAT){
        $wechat=true;
    }
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_QQ){
        $qq=true;
    }
    if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_SINA_WEIBO){
        $sina=true;
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
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

<body onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" hidden="hidden" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">账号绑定</p>
        <!--<a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>-->
    </div>
    <div class="con cshezhi_bangding clearfix">
        <ul class="list clearfix">
            <?php if(!$wechat){?>
            <li onclick="to('/wechat-user-info/connect-wechat')">
            <?php }else{?>
            <li>
                <?php }?>
                <b class="icon weixin <?php if($wechat){echo "active";}?>"></b>
                <span>微信</span>
            </li>
            <?php if(!$sina){?>
            <li onclick="to('/wechat-user-info/connect-weibo')">
            <?php }else{?>
            <li>
             <?php }?>
                <b class="icon sina <?php if($sina){echo "active";}?>"></b>
                <span>新浪微博</span>
            </li>
            <li>
                <b class="icon qq <?php if($qq){echo "active";}?>"></b>
                <span>QQ</span>
            </li>
        </ul>



    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="qq" id="val_sub">
    </form>
</div>
<script>
    function to(url)
    {
        window.location.href=url;
    }
    function submitUserInfo()
    {
        var val= $("#val_info").val();
        if(val=="")
        {alert("请输入QQ");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>
</body>
</html>