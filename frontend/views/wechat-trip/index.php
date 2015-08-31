<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/pagepiling.css" />
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <!-- 上下滚动JS/css play -->
    <script type="text/javascript" src="/assets/other/weixin/js/pagepiling.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#pagepiling').pagepiling({
                sectionsColor: ['#fff','#fff', '#fff', '#fff','#fff'],
                navigation: {
                    'position': 'right',
                    'tooltips': ['Page 1', 'Page 2', 'Page 3', 'Page 4', 'Pgae 5']
                }
            });
        });

    </script>
    <!-- 上下滚动JS/css stop -->
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
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
    <div class="Uheader header mm-fixed-top" id="menuH">
        <a href="#menu"></a>
        <div class="search_out" onclick="gotoSelect('')" >
            <input type="text" placeholder="你的旅行目的地" class="search" readonly="readonly">
            <a href="javascript:;" class="btn"><img  src="/assets/other/weixin/images/top-search.png"> </a>
        </div>
    </div>
    <!--<-->
<div id="pagepiling">
    <div class="section" id="section1" onclick="gotoSelect('澳大利亚')">
        <div class="box">
            <h2 class="title">澳大利亚</h2>
            <p class="detail">和神兽一起玩耍</p>
        </div>
        <img  src="/assets/other/weixin/images/pic01.jpg" class="pics">
    </div>
    <div class="section" id="section2" onclick="gotoSelect('香港')">
        <div class="box">
            <h2 class="title">香港</h2>
            <p class="detail">校园行一起做学霸</p>
        </div>
        <img  src="/assets/other/weixin/images/pic02.jpg" class="pics">
    </div>
    <div class="section" id="section3" onclick="gotoSelect('新加坡')">
        <div class="box" >
            <h2 class="title">新加坡</h2>
            <p class="detail">不只有鱼尾狮</p>
        </div>
        <img   src="/assets/other/weixin/images/pic03.jpg" class="pics">
    </div>
    <div class="section" id="section4" onclick="gotoSelect('意大利')">
        <div class="box">
            <h2 class="title">意大利</h2>
            <p class="detail">买买买</p>
        </div>
        <img   src="/assets/other/weixin/images/pic04.jpg" class="pics">
    </div>
    <div class="section" id="section5">
        <div class="box">
            <p class="p1">找不到您想要的体验？</p>
            <a href="/we-chat-order-list/order-view" class="btn02">订制服务</a>
        </div>
        <img src="/assets/other/weixin/images/pic05.jpg" class="pics">

    </div>
</div>
</div>
<script>
    function gotoSelect(str)
    {
        if(str=='')
        {
            window.location.href="/wechat-trip/select";
            return;
        }
        window.location.href="/wechat-trip/select-list?str="+str;
    }
</script>
</body>
</html>