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
    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>
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
        随游订单
    </div>
<div class="center_syOder">
    <div class="content">
        <div class="line clearfix">
            <ul class="syOdernav clearfix con-nav">
                <li><a href="#" class="active">新订单</a></li>
                <li><a href="#">已接单</a></li>
            </ul>
        </div>
        <div class="part clearfix TabCon" style="display:block;" >
            <?php if(empty($newList)){?>
                <p style="text-align: center;margin-top: 20%;">暂时没有可接的订单哦~</p>
            <?php } ?>
            <?php foreach($newList as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                ?>
                <div class="box clearfix" onclick="toInfo('<?php echo $val['orderNumber'] ?>')">
                    <b class="colOrange fr">￥<?php echo intval($val['totalPrice']);?></b>
                    <div class="top clearfix">
                        <a href="#" class="userPic"><img src="<?= $val['headImg']?>"></a>
                        <span class="userName"><?= $val['nickname']?></span>
                    </div>
                    <div class="text clearfix">
                        <p>预定：<?php echo $tripInfo['info']['title'];?></p>
                        <p><span>出发日期:<b><?php echo $val['beginDate'];?></b></span><span>开始时间:<b><?php echo \common\components\DateUtils::convertTimePicker($val['startTime'],2);?></b></span><span>出行人数:<b><?php echo $val['personCount'];?></b></span></p>
                    </div>
                </div>
            <?php }?>

        </div>
        <div class="part clearfix TabCon">
            <?php if(empty($list)){?>
                <p style="text-align: center;margin-top: 20%;">暂时没有已接的订单哦~</p>
            <?php } ?>
            <?php foreach($list as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                ?>
            <div class="box clearfix"  onclick="toInfo('<?php echo $val['orderNumber'] ?>')">
                <b class="colOrange fr">￥<?php echo intval($val['totalPrice']);?></b>
                <div class="top clearfix">
                    <a href="#" class="userPic"><img src="<?= $val['headImg']?>"></a>
                    <span class="userName"><?= $val['nickname']?></span>
                </div>
                <div class="text clearfix">
                    <p>预定：<?php echo $tripInfo['info']['title'];?></p>
                    <p><span>出发日期:<b><?php echo $val['beginDate'];?></b></span><span>开始时间:<b><?php echo \common\components\DateUtils::convertTimePicker($val['startTime'],2);?></b></span><span>出行人数:<b><?php echo $val['personCount'];?></b></span></p>
                </div>
            </div>
        <?php }?>
        </div>
    </div>
</div>
</div>
<script>
    function toInfo(id)
    {
        window.location.href="/wechat-user-center/trip-order-info?id="+id;
    }
</script>
</body>
</html>
