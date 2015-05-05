<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/7
 * Time : 上午9:38
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<script type="text/javascript">
    $("body").css("background","#eeeeee");
</script>

<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>

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

</style>

<!--banner开始-->
<div class="index-banner">
    <ul class="banners">
        <li class="banner01"></li>
    </ul>
    <div class="serch">
        <input type="text" value="" class="text1">
        <input type="button" value="搜索" class="btn1">
    </div>
</div>
<!--banner结束-->


<!--list开始-->
<div class="list w1200 clearfix">

    <p class="title">热门</p>
    <ul id="ul1">
        <li><img src="/assets/images/lvyou.png" alt="">
            <div class="zhezhao">
                <p>rytut剪辑剪辑剪辑剪辑</p>
                <p class="pingjia">评价<img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <span>总价：<b>800</b></span></p>
            </div>
            <p class="user01"><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt="">
            <div class="zhezhao">
                <p>rytut剪辑剪辑剪辑剪辑</p>
                <p class="pingjia">评价<img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <span>总价：<b>800</b></span></p>
            </div>
            <p class="user01"><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt="">
            <div class="zhezhao">
                <p>rytut剪辑剪辑剪辑剪辑</p>
                <p class="pingjia">评价<img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <span>总价：<b>800</b></span></p>
            </div>
            <p class="user01"><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt="">
            <div class="zhezhao">
                <p>rytut剪辑剪辑剪辑剪辑</p>
                <p class="pingjia">评价<img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <span>总价：<b>800</b></span></p>
            </div>
            <p class="user01"><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
    </ul>
</div>
<a href="#"  class="btn8">显示更多</a>

<!--list结束-->
<!--index-tuijian begin-->
<div class="index-tuijian w1200 clearfix">
    <ul class="countrys">
        <li><a href="javascript:;"><img src="/assets/images/index/01.png"></a><span>济州岛</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/02.png"></a><span>巴黎</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/03.png"></a><span>圣母院</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/04.png"></a><span>台北</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/05.png"></a><span>芬兰</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/06.png"></a><span>巴黎</span></li>
        <li><a href="javascript:;"><img src="/assets/images/index/07.png"></a><span>伦敦</span></li>
    </ul>
</div>
<a href="#"  class="btn8">显示更多</a>

<!--index-tuijian end-->



<script type="text/javascript">
    $(document).ready(function(){
        loadTrip();
    });
    function loadTrip(){
        var tripId=$("#tripId").val();
        $.ajax({
            url :'/view-trip/get-trip-list',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend:function(){
                //show load
            },
            error:function(){
                //hide load
                Main.showTip("发布随游失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    var list=data.data;
                    if(list.length==0){
                       return;
                    }
                    var trip,html="";
                    for(var i=0;i<list.length;i++){
                        trip=list[i];
                        html+='<li><img src="'+trip.titleImg+'" alt="">';
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
                        html+='<h4>'+trip.title+'</h4>';
                        html+='</li>';

                    }
                    $("#ul1").append(html);
                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }
</script>

