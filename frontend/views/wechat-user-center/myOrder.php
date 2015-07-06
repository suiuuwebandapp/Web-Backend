<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>我的订单</title>
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
<div class="center_myOder">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        我的订单
    </div>
    <div class="content">
        <?php if(count($list)==0){?>
            <img src="/assets/other/weixin/images/logo02.png" class="logo">
        <p class="noOrder">你还没有订单哦</p>
        <?php }?>
        <?php foreach($list as $val){
            $tripInfo = json_decode($val['tripJsonInfo'],true);
            ?>
            <div class="box" onclick="toInfo('<?php echo $val['orderNumber'] ?>')">
                <a href="javascript:;" class="pic"><img src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                <div class="details">
                    <h3 class="title"><?php echo $tripInfo['info']['title'];?></h3>
                    <p class="line clearfix">
                        <b class="colOrange">￥<?php echo $tripInfo['info']['basePrice'];?></b>
                        <img src="<?= $tripInfo['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $tripInfo['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $tripInfo['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $tripInfo['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        <img src="<?= $tripInfo['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    </p>
                </div>
            </div>
        <?php }?>
</div>
</div>
</div>
<script>
    function toInfo(id)
    {
        window.location.href="/wechat-user-center/my-order-info?id="+id;
    }
</script>
</body>
</html>
