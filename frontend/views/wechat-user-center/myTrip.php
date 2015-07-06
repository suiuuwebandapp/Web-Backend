<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>我的随游</title>
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
    <style>
        .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
        .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
    </style>
</head>

<body>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        我的随游
    </div>
    <div class="content">
        <?php if(count($list)==0){?>
        <img src="/assets/other/weixin/images/logo02.png" class="logo">
        <p class="noOrder">你还没有发布随游哦</p>
        <?php }?>
        <?php foreach($list as $val){?>
        <div class="box">
            <a href="javascript:;" class="pic"><img src="<?php echo $val['titleImg'];?>"></a>
            <div class="details">
                <h3 class="title"><?php echo $val['title'];?></h3>
                <p class="line clearfix">
                    <b class="colOrange">￥<?php echo $val['basePrice'];?></b>
                   <img src="<?= $val['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $val['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $val['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $val['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                    <img src="<?= $val['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                </p>
            </div>
        </div>
        <?php }?>
    </div>
</div>

</body>
</html>
