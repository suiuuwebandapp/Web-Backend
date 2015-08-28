<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
</head>

<body>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">绑定</p>
    </div>
<div class="con sy_zh clearfix">
    <div class="box">
        <img src="/assets/other/weixin/images/logo01.png" class="logo">
        <p>恭喜您！订单已生成！</p>
        <p>距离成功提交只差最后一步啦</p>
    </div>
    <div class="box">
        <p class="p1">请选择相应的传送口</p>
        <label>未有随游账号</label>
        <a href="/we-chat/register" class="btn">立即注册</a>
        <label>已有随游账号</label>
        <a href="/we-chat/binding" class="btn">账号绑定</a>
<!--        <label>等不及啦！</label>-->
<!--        <a href="/we-chat/access" class="btn">微信注册</a>-->
    </div>

</div>
</div>



</body>
</html>
