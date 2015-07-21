<!doctype html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游详情</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
</head>
<body class="bgwhite">
<div class="syDetailBanner02">
    <!--banner开始-->
    <div class="bd">
        <ul class="banners"  id="ul_id">
            <?php  foreach($info['picList'] as $pic){?>
                <li class="banner01"><img src="<?= $pic['url'];?>"></li>
            <?php }?>
        </ul>
        <!--
               <div class="banner-btn">
                    <a class="prev" href="javascript:void(0);"></a>
                    <a class="next" href="javascript:void(0);"></a>
               </div>
        -->
        <div class="hd"><ul></ul></div>
    </div>
    <!--banner结束-->
    <script type="text/javascript">
        $(document).ready(function(){
            $(".syDetailBanner02").slide({
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
<div class="syContent02 con">
    <div class="top clearfix">
        <input type="hidden" name="tripId" value="<?=$info['info']['tripId'];?>" id="tripId"/>
        <a href="javascript:;" onclick="showAndroidUserHome('<?= $info['createPublisherInfo']['userSign'];?>')" class="userPic"><img src="<?= $info['createPublisherInfo']['headImg'];?>"></a>
        <span class="userName"><?= $info['createPublisherInfo']['nickname'];?></span>
        <?php if(empty($info['attention'])||$info['attention']==false){?>
            <a href="javascript:;" class="collect" attentionIdTrip="0" id="collection_trip"  onclick="tripCollection()"></a>
        <?php  }else{?>
            <a href="javascript:;" class="collect active" attentionIdTrip="<?php echo $info['attention']['attentionId']?>" id="collection_trip"  onclick="tripCollection()"></a>
        <?php  }?>
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

</div>
<script>
    function showAndroidUserHome(user)
    {
        window.jsObj.userHomePage(user);
    }
    function jsAlert(str)
    {
        window.jsObj.jsAlert(str);
    }
    function tripCollection()
    {
        var tripId=$("#tripId").val();
        if(tripId==''||tripId==undefined||tripId==0)
        {
            jsAlert('未知的随游');
            return;
        }
        var isCollection=false;
        if($('#collection_trip').attr('class')=='collect')
        {
            $('#collection_trip').attr('class','collect active');
            isCollection = true;
        }else
        {
            $('#collection_trip').attr('class','collect');
            isCollection=false;
        }

        if(isCollection)
        {
            //添加收藏
            $.ajax({
                url :'/attention/add-collection-travel',
                type:'post',
                data:{
                    travelId:tripId,
                    app_suiuu_sign:'<?= $sign?>'
                },
                error:function(){
                    //hide load
                    jsAlert("收藏随游失败");
                    $('#collection_trip').attr('class','collect');
                    isCollection=false;
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        jsAlert("收藏成功");
                        $('#collection_trip').attr('attentionIdTrip',data.data);
                    }else{
                        jsAlert(data.data);
                        $('#collection_trip').attr('class','collect');
                        isCollection=false;
                    }
                }
            });
        }else
        {
            //取消收藏
            $.ajax({
                url :'/attention/delete-attention',
                type:'post',
                data:{
                    attentionId:$('#collection_trip').attr('attentionIdTrip'),
                    app_suiuu_sign:'<?= $sign?>'
                },
                error:function(){
                    //hide load
                    $('#collection_trip').attr('class','collect active');
                    isCollection = true;
                    jsAlert("收藏随游失败");
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        jsAlert("取消成功");
                    }else{
                        $('#collection_trip').attr('class','collect active');
                        isCollection = true;
                        jsAlert(data.data);
                    }
                }
            });
        }
    }
</script>
</body>
</html>