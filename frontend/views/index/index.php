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
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
            <h4>日本京都奈良公园一日游</h4>
        </li>
        <li><img src="/assets/images/lvyou.png" alt=""><span>一</span>
            <p><img src="/assets/images/1.png" alt=""><font>xiaolehuo</font></p>
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
                        html+='<li>' +
                        '<img src="'+trip.titleImg+'" alt=""><span>一</span>' +
                        '<p><img src="'+trip.headImg+'" alt=""><font>'+trip.nickname+'</font></p>' +
                        '<h4>'+trip.title+'</h4>' +
                        '</li>';
                    }
                    $("#ul1").append(html);
                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }
</script>

