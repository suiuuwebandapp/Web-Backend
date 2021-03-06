<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/7
 * Time : 上午9:38
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">

<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(".prev,.next").hover(function(){
            $(this).stop(true,false).fadeTo("show",1);
        },function(){
            $(this).stop(true,false).fadeTo("show",1);
        });

        $(".index-banner").slide({
            titCell:".hd ul",
            mainCell:".banners",
            effect:"fold",
            interTime:3500,
            delayTime:500,
            autoPlay:true,
            autoPage:true,
            trigger:"click"
        });

    });
</script>
<style type="text/css">

    .index .topTip li{ margin-top: 9px; }
    .index-banner .serch-out .serch .helps{ bottom: 2px; }
    #indexSearchDrop b{ float: right; margin-right: 10px;  }
    #indexSearchDrop{ font-size: 14px; }
    .index .story .list li .div01 p{line-height: 24px;}
</style>

<div class="index">
    <!--banner开始-->
    <div class="index-banner">
        <ul class="banners">
            <li class="banner01">
                <div class="w1200 clearfix">
                    <p class="p1">随心所欲游世界</p>
                    <p class="p2">这里有境外最好玩的短途体验</p>
                </div>
            </li>
            <li class="banner02" onclick="window.location.href='/static/product'">
                <div class="con">
                    <p class="p1">不想拯救世界不是一个好少年</p>
                    <p class="p2">全球志愿旅行计划开启倒计时</p>
                    <a href="javascript:;" class="jion">参加活动赢取优惠哦！</a>
                </div>
            </li>

        </ul>
        <div class="serch-out">
            <div class="serch">
                <input type="text" placeholder="你想去哪里？" class="text1" id="indexSearch" />
                <ul class="drop" id="indexSearchDrop"></ul>
                <a href="javascript:;" class="helps colGreen">如何使用随游？</a>
            </div>
        </div>
        <div class="banner-btn">
            <a class="prev" href="javascript:void(0);"></a>
            <a class="next" href="javascript:void(0);"></a>
            <!--  <div class="hd"><ul></ul></div>    -->

        </div>

    </div>
    <!--banner结束-->



    <div class="bgGreen colWit clearfix topTip">
        <ul class="inner w1200">
            <li class="fl">
                <p class="p1">价格实惠</p>
                <p class="p2">比订制旅行更优惠</p>
            </li>
            <li class="fl">
                <p class="p1">个性体验</p>
                <p class="p2">独特目的玩法</p>
            </li>
            <li class="fl">
                <p class="p1">发现新奇</p>
                <p class="p2">总有出乎意料的精彩</p>
            </li>
            <li class="fl nobd">
                <p class="p1">融入当地</p>
                <p class="p2">向当地人一样旅行</p>
            </li>
        </ul>
    </div>

    <p class="title">世界从此不同</p>
    <div class="index-tuijian w1200 clearfix">
        <ul class="countrys clearfix">
            <li><a href="<?=\common\components\SiteUrl::getTripActivityUrl(1);?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/01.jpg"></a><span>大手拉小手的旅行</span></li>
            <?php if(!empty($recommendTravel)){ ?>
                <?php $recommend=$recommendTravel[0]?>
                <li class="product">
                    <img src="<?=$recommend['titleImg']?>" height="290px"><!-- <span><?=$recommend['countryName']?></span> -->
                    <div class="div01"><p><?=$recommend['title']?></p></div>
                    <div class="div02">
                        <a href="<?=\common\components\SiteUrl::getViewUserUrl($recommend['userSign'])?>" class="user"><img src="<?=$recommend['headImg']?>"></a>
                        <p class="p1"><?=$recommend['title']?></p>
                        <p class="colGreen">￥<?=$recommend['basePrice']?></p>
                        <a href="<?=\common\components\SiteUrl::getTripUrl($recommend['tripId'])?>" class="bgGreen btn colWit">详情</a>
                    </div>
                </li>
            <?php } ?>
            <?php if(!empty($recommendTravel)){ ?>
                <?php $recommend=$recommendTravel[1]?>
                <li class="product">
                    <img src="<?=$recommend['titleImg']?>" height="290px"><!-- <span><?=$recommend['countryName']?></span> -->
                    <div class="div01"><p><?=$recommend['title']?></p></div>
                    <div class="div02">
                        <a href="<?=\common\components\SiteUrl::getViewUserUrl($recommend['userSign'])?>" class="user"><img src="<?=$recommend['headImg']?>"></a>
                        <p class="p1"><?=$recommend['title']?></p>
                        <p class="colGreen">￥<?=$recommend['basePrice']?></p>
                        <a href="<?=\common\components\SiteUrl::getTripUrl($recommend['tripId'])?>" class="bgGreen btn colWit">详情</a>
                    </div>
                </li>
            <?php } ?>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('意大利');?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/04.jpg"></a><span>意大利</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('法国');?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/05.jpg"></a><span>法国</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('日本');?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/06.jpg"></a><span>日本</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('新加坡');?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/07.jpg"></a><span>新加坡</span></li>
            <?php if(!empty($recommendTravel)){ ?>
                <?php $recommend=$recommendTravel[2]?>
                <li class="product">
                    <img src="<?=$recommend['titleImg']?>" height="290px"><!-- <span><?=$recommend['countryName']?></span> -->
                    <div class="div01"><p><?=$recommend['title']?></p></div>
                    <div class="div02">
                        <a href="<?=\common\components\SiteUrl::getViewUserUrl($recommend['userSign'])?>" class="user"><img src="<?=$recommend['headImg']?>"></a>
                        <p class="p1"><?=$recommend['title']?></p>
                        <p class="colGreen">￥<?=$recommend['basePrice']?></p>
                        <a href="<?=\common\components\SiteUrl::getTripUrl($recommend['tripId'])?>" class="bgGreen btn colWit">详情</a>
                    </div>
                </li>
            <?php } ?>
            <?php if(!empty($recommendTravel)){ ?>
                <?php $recommend=$recommendTravel[3]?>
                <li class="product">
                    <img src="<?=$recommend['titleImg']?>" height="290px"><!-- <span><?=$recommend['countryName']?></span> -->
                    <div class="div01"><p><?=$recommend['title']?></p></div>
                    <div class="div02">
                        <a href="<?=\common\components\SiteUrl::getViewUserUrl($recommend['userSign'])?>" class="user"><img src="<?=$recommend['headImg']?>"></a>
                        <p class="p1"><?=$recommend['title']?></p>
                        <p class="colGreen">￥<?=$recommend['basePrice']?></p>
                        <a href="<?=\common\components\SiteUrl::getTripUrl($recommend['tripId'])?>" class="bgGreen btn colWit">详情</a>
                    </div>
                </li>
            <?php } ?>
            <li><a href="<?=\common\components\SiteUrl::getTripActivityUrl(2);?>"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/10.jpg"></a><span>梦中的情人节</span></li>
        </ul>
    </div>

    <div class="index_list w1200 clearfix">
        <p class="title">旅行，发现与体验</p>
        <ul id="ul1" class="clearfix">
            <li>
                <img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/T01.jpg">
                <div class="div01"><a href="/view-trip/list?t=<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_EXPLORE?>"><p>慢行探索</p></a></div>
            </li>
            <li>
                <img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/T02.jpg">
                <div class="div01"><a href="/view-trip/list?t=<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_PERSONALITY?>"><p>个性玩法</p></a></div>
            </li>
            <li class="nomg">
                <img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/T03.jpg">
                <div class="div01"><a href="/view-trip/list?t=<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC?>"><p>交通服务</p></a></div>
            </li>
        </ul>
    </div>

    <p class="title">TA们的故事</p>
    <div class="story clearfix w1200">
        <ul class="list clearfix">
            <li class="fl">
                <img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/storyPic01.jpg">
                <div class="div01">
                    <h2 class="title01">他使用随游旅行</h2>
                    <p>在世界各地找到独特的体验，尝试和当地专家一起旅行</p>
                    <a href="/static?help-userFlow" class="btn colGreen">了解如何用随游的方式去旅行&gt;</a>
                </div>
            </li>
            <li class="fr">
                <img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/storyPic02.jpg">
                <div class="div01">
                    <h2 class="title01">他通过随游赚取收入</h2>
                    <p>通过随游网发布你熟悉的目的地线路活动及服务，或者带领游客旅行从而获取丰厚收入。</p>
                    <a href="/static?help-publisherFlow" class="btn colGreen">如何发布随游&gt;</a>
                </div>
            </li>
        </ul>
    </div>
    <!--
    <div class="ad">
        <a class="detailBtn" href="javascript:;">活动详情</a>
    </div>
    -->

</div>

<!--index弹层------->
<div class="indexPro clearfix">
    <a href="#" class="colses"></a>
    <ul class="icon clearfix">
        <li class="fl"><div class="pic">
                <img src="/assets/images/inP01.png" width="132" height="92">
                <p class="p1">我是游客</p>
            </div>
            <div class="text clearfix">
                <div class="line bgGreen"></div>
                <p><span class="bgGreen icon"></span><span>注册并进行身份验证</span></p>
                <p><span class="bgGreen icon"></span><span>找到适合您的旅行目的地产品</span></p>
                <p><span class="bgGreen icon"></span><span>预订并享受独特体验</span></p>
            </div>
        </li>
        <li class="fr">
            <div class="pic">
                <img src="/assets/images/inP02.png" width="116" height="93">
                <p class="p1">我想发布随游</p>
            </div>
            <div class="text clearfix">
                <div class="line bgGreen"></div>
                <p><span class="bgGreen icon"></span><span>注册并进行身份验证</span></p>
                <p><span class="bgGreen icon"></span><span>上传文字描述及图片，完成随游发布</span></p>
                <p><span class="bgGreen icon"></span><span>接收订单，获取收入</span></p>
            </div>
        </li>
    </ul>
</div>
<div class="indexAd" style="z-index: 9">
    <a href="javascript:;" class="closed"></a>
    <div class="inner"></div>
</div>

<script type="text/javascript">
    $(function(){
        $('.index-banner .serch-out .serch .helps').click(function(e) {
            $('.indexPro').animate({top:0},"slow");
        });
        $('.indexPro a.colses').click(function(e) {
            $('.indexPro').animate({top:-700},"slow");
        });
    })
</script>
<script type="text/javascript">

    var currentPage=1;
    $(document).ready(function(){

        $(".prev,.next").hover(function(){
            $(this).stop(true,false).fadeTo("show",1);
        },function(){
            $(this).stop(true,false).fadeTo("show",1);
        });

        $(".detailBtn").bind("click",function(){
            $(".sydetailPop").show();
            $(".mask").show();
        });

        $(".index-banner").slide({
            //titCell:".hd ul",
            mainCell:".banners",
            effect:"fold",
            interTime:3500,
            delayTime:500,
            autoPlay:true,
            autoPage:true,
            trigger:"click"
        });


        /*
            loadTrip();
            $("#showTripMore").bind("click",function(){
                loadTrip();
            });
            $("#searchBtn").bind("click",function(){
                tripSearch();
            });
        */
        $("#indexSearch").keypress(function(e){
            if(e.keyCode==13){
                tripSearch();
            }
        });
        $("#indexSearch").on('input',function(e){
            var search= $.trim($(this).val());
            if(search==''){
                $("#indexSearchDrop").html("");
                $("#indexSearchDrop").hide();
                return;
            }

            var rst=loadDes(search);

            if(rst!=null&&rst.length>0){
                var html='';
                for(var j=0;j<rst.length;j++){
                    var temp=rst[j];
                    var name=temp.cname+"\\"+temp.ename;
                    var tripCountHtml='<b>'+temp.count+'条随游</b>';
                    if(name.length>16){
                        name=name.substring(0,16)+"...";
                    }

                    html+='<li><a href="'+UrlManager.getTripSearchUrl(temp.cname)+'">';
                    html+=name+tripCountHtml;
                    html+='</a></li>'
                    if(j==6){
                        break;
                    }
                }
                $("#indexSearchDrop").html(html);
                $("#indexSearchDrop").show();
            }else{
                $("#indexSearchDrop").html("");
                $("#indexSearchDrop").hide();
            }
        });

    });


    function tripSearch(){
        var searchInfo=$("#indexSearch").val();
        window.location.href=UrlManager.getTripSearchUrl(searchInfo);
    }
    function loadTrip(){
        $.ajax({
            url :'/view-trip/get-recommend-trip',
            type:'post',
            data:{
                p:currentPage,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend:function(){
                //show load
            },
            error:function(){
                //hide load
                //Main.showTip("获取随游失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    var list=data.data.data;
                    var trip,html="";
                    for(var i=0;i<list.length;i++){
                        trip=list[i];
                        var title=trip.title;
                        if(title.length>13){
                            title=title.substring(0,13)+"...";
                        }
                        html+='<li><div class="box"><img src="'+trip.titleImg+'" alt="" width="284px" height="260px"></div>';
                        html+='<a href="'+UrlManager.getTripInfoUrl(trip.tripId)+'"><div class="zhezhao">';
                        html+='<p>'+trip.intro+'</p>';
                        html+='<p class="pingjia">评价';
                        html+='<img src="/assets/images/start1.fw.png" width="13" height="13">';
                        html+='<img src="/assets/images/start1.fw.png" width="13" height="13">';
                        html+='<img src="/assets/images/start1.fw.png" width="13" height="13">';
                        html+='<img src="/assets/images/start2.fw.png" width="13" height="13">';
                        html+='<img src="/assets/images/start2.fw.png" width="13" height="13">';
                        html+='<span>基础价格：<b>'+trip.basePrice+'</b></span></p>';
                        html+='</div></a>';
                        html+='<p class="user01"><img src="'+trip.headImg+'" alt="" width="40" height="40"><font>'+trip.nickname+'</font></p>';
                        html+='<h4>'+title+'</h4>';
                        html+='</li>';
                    }
                    $("#ul1").append(html);
                    return;
                    if(data.data.totalPage==currentPage){
                        $("#showTripMore").html("暂无更多");
                        $("#showTripMore").unbind("click");

                    }
                    currentPage++;
                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }
</script>
