<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
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

<body  class="bgwhite" onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">服务项目选择</p>
    </div>
    <script type="text/javascript">
        $(function(){
            $('.jtseverSelect .box .items').click(function(e) {
                $(this).next('.items_drop').toggle();
            });
        })
    </script>
    <div class="con jtseverSelect clearfix">
        <div class="box">
            <p class="items">包车</p>
            <div class="items_drop">
                <input type="text" placeholder="预约日期（当地）">
                <input type="text" placeholder="时间（当地）">
                <input type="text" placeholder="人数">

            </div>
        </div>
        <div class="box">
            <p class="items">接机</p>
            <div class="items_drop">
                <input type="text" placeholder="预约日期（当地）">
                <input type="text" placeholder="时间（当地）">
                <input type="text" placeholder="人数">

            </div>
        </div>
        <div class="box">
            <p class="items">送机</p>
            <div class="items_drop">
                <input type="text" placeholder="预约日期（当地）">
                <input type="text" placeholder="时间（当地）">
                <input type="text" placeholder="人数">

            </div>
        </div>
        <div class="box">
            <p>包车：<span>2015.09.09</span> <span>09.30am</span><span>￥500</span></p>
            <p>包车：<span>2015.09.09</span> <span>09.30am</span><span>￥500</span></p>
            <p>包车：<span>2015.09.09</span> <span>09.30am</span><span>￥500</span></p>
            <p class="money">总价：<span>1500¥</span></p>
        </div>
        <div class="btns clearfix">
            <a href="###" class="btn btn01">确定</a>
        </div>

    </div>
</div>




</body>
</html>
