<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游详情</title>
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
</head>
<body class="bgwhite">
<div class="syDetailBanner">
    <!--banner开始-->
    <div class="bd">
        <ul class="banners" id="ul_id">
            <?php  foreach($info['picList'] as $pic){?>
            <li class="banner01"><img src="<?= $pic['url'];?>"></li>
            <?php }?>
        </ul>
        <!--<div class="banner-btn">
            <a class="prev" href="javascript:void(0);"></a>
            <a class="next" href="javascript:void(0);"></a>
        </div>-->
        <div class="hd"><ul></ul></div>
    </div>
    <!--banner结束-->
    <script type="text/javascript">
        $(document).ready(function(){

            /*$(".prev,.next").hover(function(){
                $(this).stop(true,false).fadeTo("show",1);
            },function(){
                $(this).stop(true,false).fadeTo("show",1);
            });*/

            $(".syDetailBanner").slide({
                titCell:".hd ul",
                mainCell:".banners",
                effect:"fold",
                interTime:3500,
                delayTime:500,
                autoPlay:true,
                autoPage:true,
                trigger:"click"
            });
            $("#ul_id").css("height",$("#ul_id").width()/830*466);
        });
    </script>
</div>
<div class="syContent con">
    <div class="top clearfix">
        <a href="#" class="userPic"><img src="<?= $info['createPublisherInfo']['headImg'];?>"></a>
        <span class="userName"><?= $info['createPublisherInfo']['nickname'];?></span>
    </div>
    <p><?= $info['info']['title'];?></p>
    <p class="line clearfix">
        <b class="colOrange">￥<?= $info['info']['basePrice'];?></b>
        <img src="<?= $info['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
    </p>
    <iframe id="mapFrame" name="mapFrame" src="/google-map/view-scenic-map?tripId=<?=$info['info']['tripId'];?>" width="100%" height="230px;" frameborder="0" scrolling="no" style="margin: 20px 0px "></iframe>
    <ul class="details clearfix">
        <li>
            <span class="icon icon1">随游
                <b> <?= \common\components\DateUtils::convertTimePicker($info['info']['startTime'],2) ?>  - <?= \common\components\DateUtils::convertTimePicker($info['info']['endTime'],2) ?> </b>
            </span>
        </li>
        <li><span class="icon icon2">随游时长<b id="tripTime"><?= $info['info']['travelTime']?></b>小时</span></li>
        <li class="last"><span class="icon icon3">随友最多接待<b id="maxPeopleCount"><?= $info['info']['maxUserCount']?></b>人</span></li>
    </ul>
    <h3 class="title colBlue">详情描述</h3>
    <p><?= nl2br($info['info']['info'])?></p>
    <h3 class="title colBlue">价格内容</h3>
    <div class="contian clearfix">
        <?php foreach($info['includeDetailList'] as $val){?>
            <span><img src="/assets/other/weixin/images/syhas.png"><?php echo $val['name']?></span>
        <?php }?>
        <?php foreach($info['unIncludeDetailList'] as $val){?>
            <span><img src="/assets/other/weixin/images/syno.png"><?php echo $val['name']?></span>
        <?php }?>
    </div>
    <div class="btns clearfix">
        <a href="#" class="bgOrange fl">咨询</a>
        <a href="/wechat-trip/add-order-view?tripId=<?=$info['info']['tripId'];?>" class="bgBlue fr">预定</a>

    </div>
</div>
</body>
</html>