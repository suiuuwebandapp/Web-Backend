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
        <p class="navTop">他的心愿</p>
    </div>
    <div class="con g_xinyuan">
        <div class="content">
            <ul class="list clearfix">
                <?php foreach($list["data"] as $val){?>
                <li onclick="toInfo('<?=$val["tripId"]?>')">
                    <a href="javascript:;" class="pic"><img src="<?php echo $val["titleImg"]?>"></a>
                    <p><?php echo  mb_strlen($val['title'])>10?mb_substr($val['title'],0,10,"utf-8")."...":$val['title'] ?></p>
                    <p class="bottom">
                        <a href="javascript:;" class="colt"><?php echo $val['collectCount']?></a>
                        <a href="javascript:;" class="rest"><?php echo $val['commentCount']?></a>
                    </p>
                </li>
                <?php }?>
            </ul>


        </div>
    </div>
</div>
<script>
    function toInfo(id)
    {
        window.location.href="/wechat-trip/info?tripId="+id;
    }
</script>
</body>
</html>