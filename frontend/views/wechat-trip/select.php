<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
</head>

<body class="bgwhite">
<div class="con w_suiyou02 clearfix" id="goToTrip">
    <div class="search_out clearfix">
        <div class="search fl">
            <input type="text" placeholder="输入你感兴趣的国家/地点">
            <a href="#" class="searchIcon"><img src="/assets/other/weixin/images/top-search.png"></a>
        </div>
        <input type="button" value="取消" class="cancel fr" onclick="toIndex()">

    </div>
    <div class="clearfix">
        <p class="title">推荐</p>
        <span>香港</span><span>新加坡</span>
    </div>
    <div class="clearfix">
        <p class="title">亚洲</p>
        <span>台湾</span><span>日本</span><span>韩国</span><span>泰国</span><span>马来西亚</span>
    </div>

    <div class="clearfix">
        <p class="title">欧洲</p>
        <span>法国</span><span>德国</span><span>英国</span><span>荷兰</span><span>瑞士</span>
        <span>意大利</span><span>西班牙</span><span>葡萄牙</span><span>奥地利</span><span>比利时</span>
    </div>
</div>

<script>
    $("#goToTrip span").bind("click",function(){
        toSelect($(this).html());
    });

    function toSelect(str)
    {
        if(str==""||str==undefined||||str=="undefined")
        {
            alert("异常的选择");
            return;
        }

    }
    function toIndex()
    {
        window.location.href="/wechat-trip/index"
    }
</script>

</body>
</html>
