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
        <p class="navTop"><?php echo $userInfo['nickname'];?></p>
    </div>
    <div class="con g_zhuye">
        <div class="content">
            <div class="topUser clearfix">
                <div class="top clearfix">
                    <a href="/wechat-user-center/user-message-info?rUserSign=<?= $userInfo['userSign'];?>" class="chat"></a>
                    <a href="javascript:;" class="usePic fl">
                        <img src="<?php echo $userInfo['headImg'];?>">
                    </a>
                    <div class="texts fl">
                        <p class="adress"><?php $str=""; if(!empty($userInfo['countryCname'])&&!empty($userInfo['cityCname'])){$str="、";} echo $userInfo['countryCname'].$str.$userInfo['cityCname'];?></p>
                        <?php if(!empty($userInfo['email'])){?>
                            <p class="email">邮箱验证</p>
                        <?php }?>
                        <?php if(!empty($userInfo['phone'])){?>
                            <p class="email">手机验证</p>
                        <?php }?>
                    </div>
                </div>
                <?php
                $info=!empty($userInfo['info'])?nl2br($userInfo['info']):"暂无简介" ;
                $str="";
                if(mb_strlen($info,"utf-8")>80){$str=mb_substr($info,0,80,"utf-8"); $str.="....";}
                ?>
                <p id="userInfo" info="<?php echo $info?>" str="<?php echo $str;?>" isInfo="<?php if(empty($str)){echo 1;}else{echo 0;}?>"><?php echo empty($str)?$info:$str; ?></p>
                <a href="javascript:;" onclick="clickInfo()" id="infoBtn" class="drop"></a>
            </div>


                <?php if(count($tripList)==0){?>
                    <div class="bigBox nothing">
                        <h2 class="tyleTitle">他的随游(0)</h2>
                        <p>还没有随游哦</p>
                        <img src="/assets/other/weixin/images/nothing.fw.png">
                    </div>
                <?php }else{?>
            <div class="bigBox" onclick="toTripList('<?=$userInfo['userSign'] ?>')">
                    <h2 class="tyleTitle">他的随游(<?php echo count($tripList);?>)</h2>
                    <div class="box">
                        <a href="javascript:;" class="pic"><img src="<?php echo $tripList[0]['titleImg']?>"></a>
                        <div class="details">
                            <h3 class="title"><?php echo $tripList[0]['title']?></h3>
                            <p class="line clearfix">
                                <b class="colOrange">￥<?php echo $tripList[0]['basePrice']?></b>
                                <img src="<?= $tripList[0]['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                                <img src="<?= $tripList[0]['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                                <img src="<?= $tripList[0]['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                                <img src="<?= $tripList[0]['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                                <img src="<?= $tripList[0]['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            </p>
                        </div>
                    </div>
            </div>
                <?php }?>


                <?php if($attention['msg']->totalCount==0){?>
                    <div class="bigBox nothing">
                        <h2 class="tyleTitle">他的心愿单(0)</h2>
                        <p>还没有心愿单哦</p>
                        <img src="/assets/other/weixin/images/liwu-2.fw.png">
                    </div>
                <?php }else{?>
            <div class="bigBox"  onclick="toAttentionList('<?=$userInfo['userSign'] ?>')">
                <h2 class="tyleTitle">他的心愿单(<?php echo $attention['msg']->totalCount?>)</h2>
                <div class="box">
                    <a href="javascript:;" class="pic"><img src="<?php echo $attention['data'][0]['titleImg']?>"></a>
                    <div class="details">
                        <h3 class="title"><?php echo $attention['data'][0]['title']?></h3>
                        <p class="line clearfix">
                            <b class="colOrange">￥<?php echo $attention['data'][0]['basePrice']?></b>
                            <img src="<?= $attention['data'][0]['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $attention['data'][0]['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $attention['data'][0]['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $attention['data'][0]['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $attention['data'][0]['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                        </p>
                    </div>
                </div>
            </div>
                <?php }?>

        </div>
    </div>
</div>
<script>
    function clickInfo()
    {
        var isInfo = $("#userInfo").attr("isInfo");
        var str = $("#userInfo").attr("str");
        var info = $("#userInfo").attr("info");
        if(str=="")
        {return;}
        if(isInfo==1)
        {
            $("#infoBtn").attr("class","drop");
            $("#userInfo").html(str);
            $("#userInfo").attr("isInfo",0);
        }else
        {
            $("#infoBtn").attr("class","drop02");
            $("#userInfo").html(info);
            $("#userInfo").attr("isInfo",1);
        }
    }
    function toTripList(sign)
    {
        window.location.href="/wechat-user-info/trip-list?userSign="+sign;
    }
    function toAttentionList(sign)
    {
        window.location.href="/wechat-user-info/attention-list?userSign="+sign;
    }
</script>
</body>
</html>
