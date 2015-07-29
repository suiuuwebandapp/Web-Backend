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
<style type="text/css">
    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 13px;
        color: dimgray;
        padding-top:0 ;
        margin: 0 auto;
        border: none;
    }
    .select2-drop {
        font-size: 14px;
    }

    .select2-highlighted {
        background-color: #0088e4;
    }
    .select2-no-results {
        font-size: 13px;
        color: dimgray;
        text-align: center;
    }
    .index .topTip li{
        margin-top: 9px;
    }
    .index-banner .serch-out .serch .helps{
        bottom: 2px;
    }

</style>

<div class="index">
    <!--banner开始-->
    <div class="index-banner">
        <ul class="banners">
            <li class="banner01">
                <div class="w1200 clearfix">
                    <p class="p1">用随游的方式去旅行</p>
                    <p class="p2">找到全世界的目的地体验产品</p>
                </div>
            </li>
        </ul>
        <div class="serch-out">
            <div class="serch">
                <input type="text" placeholder="你想去哪里？" class="text1" id="indexSearch">
                <a href="javascript:;" class="helps colGreen">如何使用随随游？</a>
            </div>
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

    <!--list开始-->
    <div class="index_list w1200 clearfix">
        <p class="title">旅行，发现与体验</p>
        <ul id="ul1" class="clearfix">
            <?php if(!empty($recommendTravel)){ ?>
                <?php foreach($recommendTravel as $key=>$recommend){ ?>
                    <li <?=($key+1)%3==0?"class='nomg'":""; ?>>
                        <img src="<?=$recommend['titleImg']?>" width="380px" height="290px">
                        <div class="div01"><p><?=$recommend['title']?></p></div>
                        <div class="div02">
                            <a href="javascript:;" class="user"><img src="<?=$recommend['headImg']?>"></a>
                            <p class="p1"><?=$recommend['title']?></p>
                            <p class="colGreen">￥<?=$recommend['basePrice']?></p>
                            <a href="<?=\common\components\SiteUrl::getTripUrl($recommend['tripId'])?>" class="bgGreen btn colWit">详情</a>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <!--list结束-->
    <p class="title">世界从此不同</p>

    <div class="index-tuijian w1200 clearfix">
        <ul class="countrys">
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('香港');?>"><img src="/assets/images/index/01.jpg"></a><span>香港</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('西班牙');?>"><img src="/assets/images/index/02.jpg"></a><span>西班牙</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('澳大利亚');?>"><img src="/assets/images/index/03.jpg"></a><span>澳大利亚</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('新加坡');?>"><img src="/assets/images/index/04.jpg"></a><span>新加坡</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('美国');?>"><img src="/assets/images/index/05.jpg"></a><span>美国</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('法国');?>"><img src="/assets/images/index/06.jpg"></a><span>法国</span></li>
            <li><a href="<?=\common\components\SiteUrl::getTripSearchUrl('意大利');?>"><img src="/assets/images/index/07.jpg"></a><span>意大利</span></li>
        </ul>
    </div>

    <p class="title">TA们的故事</p>

    <div class="story clearfix w1200">
        <div class="fl left"><a href="#"><img src="/assets/images/index/vide.jpg" width="400" height="257"></a></div>
        <div class="fr right clearfix">
            <p>谁是随友 ？</p>
            <p> TA们不是简单导游、伴游或者语言翻译，他们是在当地生活多年的人，熟知自己的生活领域从而在门道分享该领域的体验，他们是历史控、音乐玩咖、运动健将、购物狂、吃货……他们是任何人，他们带你深入当地生活，让你不虚此行。 随游，致力于打造为全世界用户提供体验目的地独特服务的在线平台。。。</p>
            <a href="###" class="bgGreen btn colWit fr">详情</a>
        </div>
    </div>


    <div class="w1200 ad">
        <a class="detailBtn activityBanner" href="javascript:;">活动详情</a>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function(){

        $(".prev,.next").hover(function(){
            $(this).stop(true,false).fadeTo("show",1);
        },function(){
            $(this).stop(true,false).fadeTo("show",1);
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
    });
</script>
<script type="text/javascript">

    var currentPage=1;
    $(document).ready(function(){
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
                        html+='<a href="/view-trip/info?trip='+trip.tripId+'"><div class="zhezhao">';
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
