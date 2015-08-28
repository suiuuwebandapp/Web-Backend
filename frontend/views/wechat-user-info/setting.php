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
</head>

<body>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">设置</p>
    </div>
    <div class="con cshezhi clearfix">
        <p class="title">个人信息</p>
        <div class="box">
            <p>昵称：<span><?= $userInfo['nickname'];?></span></p>
            <p>电话：<span><?= empty($userInfo['phone'])?"待完善":$userInfo['phone'];?></span></p>
            <p>邮箱：<span><?= empty($userInfo['email'])?"待完善":$userInfo['email'];?></span></p>
            <a href="###" class="setBtn"></a>
        </div>
        <p class="title">关于随游</p>
        <div class="box" id="list">
            <ul class="list">
                <li onclick="to('/wechat-user-info/supply')">我们提供</li>
                <li onclick="to('/wechat-user-info/notice')">订购须知</li>
                <li onclick="to('/wechat-user-info/contact')">联系我们</li>
            </ul>
        </div>
    </div>
</div>
<script>
    function to(url)
    {
        window.location.href=url;
    }
</script>
</body>
</html>