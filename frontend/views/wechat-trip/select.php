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
    <style type="text/css">
        .selectItemBox{border:1px solid #eee; width: 80%; position:absolute;background: #fff;display: none}
        .selectItem{display:none;color: #858585;padding-left: 5px;margin-top: 10px; width: 80%;}
      /*  a:hover{background: #000033}*/
    </style>
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
<div id="page" hidden="hidden" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">搜索</p>
    </div>
<div class="con w_suiyou02 clearfix" id="goToTrip">
    <div class="search_out clearfix">
        <div class="search fl">
            <input id="country_search" style="width: 80%" type="text" placeholder="输入你感兴趣的国家/地点">
            <a href="#" class="searchIcon"><img src="/assets/other/weixin/images/top-search.png"></a>
        </div>
        <input type="button" value="取消" class="cancel fr" onclick="toIndex()">
    </div>
    <div style="  position: relative">
        <div  class="selectItemBox">
            <?php foreach($cList as $cval){?>
            <a onclick="toSelect('<?= $cval['cname']?>');"><input type="text" class="selectItem" value="<?= $cval['cname']?>"></a>
            <?php }?>
            <?php foreach($ctList as $ctval){?>
                <a onclick="toSelect('<?= $ctval['cname']?>');"><input type="text" class="selectItem" value="<?= $ctval['cname']?>"></a>
            <?php }?>
        </div>
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
</div>
<script>
    $("#country_search").keyup(function(){
        var val=$("#country_search").val();
        if(val!='')
        {
            $('.selectItemBox').show();
            $('.selectItemBox').find('input').css('display','none');
            $('.selectItemBox').find('input').each(function(){
                if($(this).val().indexOf(val)!=-1){
                    $(this).show();
                }else
                {
                    $(this).hide();
                }
            });
        }else
        {
            $('.selectItemBox').hide();
        }
    });
    $("#goToTrip span").bind("click",function(){
        toSelect($(this).html());
    });

    function toSelect(str)
    {
        if(str==""||str==undefined||str=="undefined")
        {
            alert("异常的选择");
            return;
        }
        window.location.href="/wechat-trip/select-list?str="+str;
    }
    function toIndex()
    {
        window.location.href="/wechat-trip/index"
    }
</script>

</body>
</html>
