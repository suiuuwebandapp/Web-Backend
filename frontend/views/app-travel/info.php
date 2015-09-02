<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link type="text/css" rel="stylesheet" href="/assets/other/app/css/astyle.css">
    <script type="text/javascript" src="/assets/other/app/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/app/js/jquery.SuperSlide.2.1.1.js"></script>
</head>

<body  class="bgwhite" >
<div class="syDetailBanner">
    <span class="smoney">￥<?= $info['info']['basePrice'];?></span>
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
        <a href="javascript:;" onclick="showAndroidUserHome('<?= $info['createPublisherInfo']['userSign'];?>')" class="userPic"><img src="<?= $info['createPublisherInfo']['headImg'];?>"></a>
        <span class="userName"><?= $info['createPublisherInfo']['nickname'];?></span>
        <p class="adress"><?= $info['createPublisherInfo']['countryName'].$info['createPublisherInfo']['cityName'];?></p>
        <a href="javascript:;" class="collect <?php if(count($info['attention'])!=0){echo "active";}?>" id="collection_trip" attentionIdTrip="<?php if(count($info['attention'])!=0){echo $info['attention'][0]['attentionId'];}?>"></a>
    </div>
    <p class="bq"><span><?= $info['info']['tags'];?></span></p>
    <ul class="details clearfix">
        <li>
                <span class="icon icon1">同伴最多
                    <b> <?= $info['info']['maxUserCount'];?>人</b>
                </span>
        </li>
        <li><span class="icon icon2"><?=  substr($info['info']['startTime'],0,5) ?>  -<?=  substr($info['info']['endTime'],0,5) ?> </span></li>
        <li class="last"><span class="icon icon3"><?= $info['info']['travelTime'];?>小时</span></li>
    </ul>
    <div class="bgbox">
        <h3 class="title colGreen">详情描述</h3>
        <p><?= nl2br($info['info']['info'])?></p>
        <?php foreach($info['specialList'] as $val){?>
            <img src="<?php echo $val['picUrl']?>" class="pics">
            <p class="title01"><?php echo nl2br($val['title'])?></p>
            <p><?php echo nl2br($val['info'])?></p>
        <?php }?>
    </div>
    <?php if($userRecommend!=null&&!empty($userRecommend['content'])){ ?>
        <div class="tuijian bgGreen clearfix">
            <a href="#" class="left"><img src="<?=$userRecommend['headImg'];?>"></a>
            <div class="right">
                <p>推荐理由：</p>
                <p><?=nl2br($userRecommend['content'])?></p>
            </div>
        </div>
    <?php }?>
    <h3 class="title colBlue">价格内容</h3>
    <div class="contian clearfix">
        <?php foreach($info['includeDetailList'] as $val){?>
            <span><img src="/assets/other/weixin/images/syhas.png"><?php echo $val['name']?></span>
        <?php }?>
        <?php foreach($info['unIncludeDetailList'] as $val){?>
            <span><img src="/assets/other/weixin/images/syno.png"><?php echo $val['name']?></span>
        <?php }?>
    </div>
    <p class="line clearfix">
        <b>用户评价<?php echo count($comment['data']);?></b>
        <img src="<?= $info['info']['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
        <img src="<?= $info['info']['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
    </p>

</div>
</body>
<script>
    function showAndroidUserHome(user)
    {
        window.jsObj.userHomePage(user);
    }
    function jsAlert(str)
    {
        window.jsObj.jsAlert(str);
    }
    $('#collection_trip').bind('click',submitCollection);
    /**
     * 添加收藏
     */
    function submitCollection() {
        var tripId = "<?=$info['info']['tripId'];?>";
        if (tripId == '' || tripId == undefined || tripId == 0) {
            jsAlert('未知的随游');
            return;
        }
        var isCollection = false;
        if ($('#collection_trip').attr('class') == 'collect '||$('#collection_trip').attr('class') == 'collect') {
            $('#collection_trip').addClass('active');
            isCollection = true;
        } else {
            $('#collection_trip').removeClass('active');
            isCollection = false;
        }
        if (isCollection) {
            //添加收藏
            $.ajax({
                url: 'http://api.suiuu.com/v1/app-attention/add-collection-travel'+"?token="+"<?php echo urlencode($token);?>",
                type: 'post',
                data: {
                    travelId: tripId
                },
                error: function () {
                    //hide load
                    jsAlert("收藏随游失败");
                    $('#collection_trip').removeClass('active');
                    isCollection = false;
                },
                success: function (data) {
                    //hide load
                    data = eval("(" + data + ")");
                    if (data.status == 1) {
                        jsAlert("收藏成功");
                        $('#collection_trip').attr('attentionIdTrip', data.data);
                        isCollection = true;
                    } else if (data.status == -3) {
                        $('#collection_trip').removeClass('active');
                        jsAlert("请登录后再收藏");
                        isCollection = false;
                    } else {
                        $('#collection_trip').removeClass('active');
                        jsAlert(data.data);
                        isCollection = false;
                    }
                }
            });
        } else {
            //取消收藏
            $.ajax({
                url: 'http://api.suiuu.com//v1/app-attention/delete-attention'+"?token="+"<?php echo urlencode($token);?>",
                type: 'post',
                data: {
                    attentionId: $('#collection_trip').attr('attentionIdTrip')
                },
                error: function () {
                    //hide load
                    $('#collection_trip').addClass('active');
                    isCollection = true;
                    jsAlert("收藏随游失败");
                },
                success: function (data) {
                    //hide load
                    data = eval("(" + data + ")");
                    if (data.status == 1) {
                        jsAlert("取消成功");
                        isCollection = false;
                    } else if (data.status == -3) {
                        $('#collection_trip').addClass('active');
                        isCollection = true;
                        jsAlert("请登录后再取消");
                    }else{
                        $('#collection_trip').addClass('active');
                        isCollection = true;
                        jsAlert(data.data);
                    }
                }
            });
        }
    }
</script>
</html>