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
    .select2-container .select2-choice {jquery.SuperSlide.2.1.1.js
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

</style>

<!--banner开始-->
<div class="index-banner">
    <ul class="banners">
        <li class="banner01">
            <div class="w1200 clearfix">
                <p class="p1">随游</p>
                <p class="p2">让分享成为一种旅行方式</p>
            </div>
        </li>
        <li class="banner02">
            <div class="w1200 clearfix">
                <p class="p1">微信新添定制功能</p>
                <p class="p2">助你任性出游<a href="#wei">关注随游</a></p>
            </div>
        </li>
        <li class="banner03">
            <div class="w1200 clearfix">
                <p class="p1">去意大利吧</p>
                <p class="p2">边吃提拉米苏边看世博会</p>
            </div>
        </li>
        <li class="banner04">
            <div class="w1200 clearfix">
                <p class="p0"><img src="<?=Yii::$app->params['suiuu_image_url']?>/suiuu_index/b4-wenzi.png" width="411" height="172"></p>
            </div>
        </li>
    </ul>
    <div class="banner-btn">
        <a class="prev" href="javascript:void(0);"></a>
        <a class="next" href="javascript:void(0);"></a>
    </div>
    <!--    <div class="hd"><ul></ul></div>    -->

    <div class="serch-out">
        <div class="serch">
            <input type="text" value="" class="text1" id="search">
            <input type="button" value="搜索" class="btn1"  id="searchBtn">
        </div>
    </div>
</div>
<!--banner结束-->



<div class="index_list w1200 clearfix">
    <p class="title">热门</p>
    <ul id="ul1">
        <li>
            <a href="/view-trip/list?s=澳大利亚"><img src="/assets/images/index/pic01.jpg" width="285" height="340"></a>
        </li>
        <li>
            <a href="/view-trip/list?s=香港"><img src="/assets/images/index/pic02.jpg" width="285" height="340"></a>
        </li>
        <li>
            <a href="/view-trip/list?s=新加坡"><img src="/assets/images/index/pic03.jpg" width="285" height="340"></a>
        </li>
        <li>
            <a href="/view-trip/list?s=意大利"><img src="/assets/images/index/pic04.jpg" width="285" height="340"></a>
        </li>
    </ul>
    <a href="/view-trip/list"  class="btn8" id="showTripMore">显示更多</a>
</div>



<div class="index-tuijian w1200 clearfix">
    <ul class="countrys">
        <li><a href="/view-trip/list?s=香港"><img src="/assets/images/index/01.jpg"></a><span>香港</span></li>
        <li><a href="/view-trip/list?s=西班牙"><img src="/assets/images/index/02.jpg"></a><span>西班牙</span></li>
        <li><a href="/view-trip/list?s=澳大利亚"><img src="/assets/images/index/03.jpg"></a><span>澳大利亚</span></li>
        <li><a href="/view-trip/list?s=新加坡"><img src="/assets/images/index/04.jpg"></a><span>新加坡</span></li>
        <li><a href="/view-trip/list?s=美国"><img src="/assets/images/index/05.jpg"></a><span>美国</span></li>
        <li><a href="/view-trip/list?s=法国"><img src="/assets/images/index/06.jpg"></a><span>法国</span></li>
        <li><a href="/view-trip/list?s=意大利"><img src="/assets/images/index/07.jpg"></a><span>意大利</span></li>
    </ul>
    <a href="/destination/list" class="btn8">显示更多</a>
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
        //loadTrip();
        /*$("#showTripMore").bind("click",function(){
            loadTrip();
        });*/
        $("#searchBtn").bind("click",function(){
            tripSearch();
        });
        $("#search").keypress(function(e){
            if(e.keyCode==13){
                $("#searchBtn").click();
            }
        });
    });

    function tripSearch(){
        var searchInfo=$("#search").val();
        window.location.href="/view-trip/list?s="+encodeURIComponent(searchInfo);
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
