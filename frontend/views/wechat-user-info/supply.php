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
        <p class="navTop">我们提供</p>
    </div>
    <div class="con cshezhi_tigong clearfix">
        <p>随游(suiuu.com)最初的灵感来源于一批热爱旅行及分享的年轻人。团队最初成立于新加坡，随后与2015年初在上海正式注册为上海明浪信息科技有限公司。

        <p>随游是一个专注于海外目的地旅行体验的社区型服务平台，在这里人们既是导游也是旅行者，可以通过网站，微信，移动设备等各种方式发布，分享，预订全球的各种独特旅行体验及服务。

        <p>无论您想体验不一样的异国文化，或和当地人结伴同行，甚至只是在旅行途中需要达人的帮助指点。您都能以任何价位享受到随游在全球多个城市为您带来的独一无二的旅行体验。随游相信用户提供的价值，我们不仅是旅行体验服务平台，更希望能够成为所有旅行者喜爱的旅行分享社区。</p>
    </div>
</div>

</body>
</html>