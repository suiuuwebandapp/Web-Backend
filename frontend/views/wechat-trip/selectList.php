<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
</head>

<body>
<div class="w_suiyou">
    <nav class="top"><span class="fl"><?php echo $str?></span><a href="/wechat-trip/search?str=<?php echo $str;?>&tag=<?php echo $tag;?>&peopleCount=<?php echo $peopleCount;?>&amount=<?php echo $amount;?>" class="btn fr">...</a></nav>
    <div class="content">
        <?php foreach($list as $val){?>
        <div class="box">
            <a href="" class="pic"><img src="<?php echo $val['titleImg']?>"></a>
            <div class="details">
                <h3 class="title"><?php echo $val['title']?></h3>
                <p class="line clearfix">
                    <b class="colOrange">￥<?php echo $val['basePrice']?></b>
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

</body>
</html>
