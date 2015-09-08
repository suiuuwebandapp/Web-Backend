<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午7:11
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<link type="text/css" rel="stylesheet" href="/assets/css/jquery-ui.css">
<style type="text/css">
    body{
        background-color: #F7F7F7;
    }
    #trip_base_list{
        min-height: 400px;
    }
</style>

<script type="text/javascript" src="/assets/js/UI/jquery-ui.js"></script>
<script type="text/javascript" src="/assets/js/jquery.lazyload.min.js"></script>


<div class="sylx w1200 clearfix">
    <div class="sylx-xiangxi clearfix">
        <h3 class="tit bgGreen">更多筛选条件</h3>
        <div class="box clearfix">
            <p class="ptitle">分类：</p>
            <p class="p3 clearfix" id="typeList">
                <span <?=$type==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_EXPLORE?'class="active"':''?>
                    dataType="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_EXPLORE?>">慢行探索</span>
                <span <?=$type==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_PERSONALITY?'class="active"':''?>
                    dataType="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_PERSONALITY?>">个性玩法</span>
                <span <?=$type==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC?'class="active"':''?>
                    dataType="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC?>">交通服务</span></p>
            <p class="ptitle">成员:</p>
            <p class="p1 clearfix">
                <a href="javascript:;" class="icon jian" id="subtract"></a>
                <input type="text" id="peopleCount" value="0">
                <a href="javascript:;" class="icon add" id="add"></a>
            </p>
            <p class="ptitle">类型:</p>
            <p class="p2 clearfix" id="tagList">
                <?php foreach($tagList as $tag){ ?>
                    <span><?=$tag?></span>
                <?php }?>
            </p>
            <div class="price-select">
                <p>
                    <label for="amount">价格:</label>
                    <input type="text" id="amount">
                </p>
                <div id="slider-range"></div>
            </div>
        </div>
    </div>
    <div class="containers clearfix">
        <div class="select" style="padding-top: 10px">
            <a href="javascript:;" class="btns fl" id="showHot">热门</a>
            <p class="result fl"><span id="searchResultCount"><?=$pageResult->totalCount>100?'100+':$pageResult->totalCount.'条';?></span>&nbsp;&nbsp;搜索结果</p>
            <div class="math fr">
                <p id="orderType" data-order="1">排序：默认</p>
                <ul class="sel fl" id="orderList">
                    <li data-order="1">推荐分数</li>
                    <li data-order="2">预订数</li>
                    <li data-order="3">评论数</li>
                </ul>
            </div>
        </div>
        <div class="sylx-list clearfix" id="trip_base_list">
            <?php if($pageResult->result!=null&&count($pageResult->result)>0){ ?>
                <?php foreach($pageResult->result as $key=> $trip){?>
                    <div class="web-tuijian fl <?=($key+1)%2==0?'nomg':''?>">
                        <a href="<?=\common\components\SiteUrl::getTripUrl($trip['tripId'])?>" class="pic">
                            <?php if($trip['isHot']==1){ ?>
                                <span class="hot"></span>
                            <?php } ?>
                            <img src="<?=$trip['titleImg']?>" width="410" height="267">
                            <p class="p4"><span>￥<?= intval($trip['basePrice']) ?></span>
                                <?=$trip['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?'每次':'每人'?>
                            </p>
                        </a>
                        <a target="_blank" href="<?=\common\components\SiteUrl::getViewUserUrl($trip['userSign'])?>" class="user"><img src="<?=$trip['headImg'];?>" ></a>
                        <p class="title"><?=mb_strlen($trip['title'],"UTF-8")>20?mb_substr($trip['title'],0,20,"UTF-8")."...":$trip['title'] ?></p>
                        <p class="xing">
                            <img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                            <img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                            <img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                            <img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                            <img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                            <span><?=$trip['tripCount']?>人去过</span><span><?=empty($trip['commentCount'])?'0':$trip['commentCount']?>条评论</span>
                        </p>
                    </div>
                <?php } ?>
            <?php }else{
                echo "<div style='min-height:400px;text-align: center;height: 200px;line-height: 200px;'>暂时没有找到相关随游</div>";
            }?>
        </div>
        <ol id="spage" class="clearfix">
            <?= $pageResult->pageHtml?>
        </ol>
    </div>
    <div class="syBanner clearfix">
        <a href="javascript:;" class="detailBtn">活动详情</a>
    </div>

</div>





<script type="text/javascript">
    var currentPage=1;
    $(function(){
        $('.sylx .sylx-xiangxi .p2 span').click(function(e) {
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
            currentPage=1;
            searchTip();
        });

    });

    /*-----随游-价格区间拖动条----*/
    $(function() {
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 10000,
            values: [ 0, 10000 ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "￥" + ui.values[ 0 ] + " - ￥" + ui.values[ 1 ] );
            },
            stop:function(){
                searchTip();
            }
        });
        $( "#amount" ).val( "￥" + $( "#slider-range" ).slider( "values", 0 ) +
        " - ￥" + $( "#slider-range" ).slider( "values", 1 ) );
    });

    $(document).ready(function(){

        $(document).scroll(function () {
            var documentHeight=$(document).height();//浏览器时下窗口可视区域高度
            var top=parseInt($(".sylx-xiangxi").css("top").replace("px",''));
            var fixHeight=$(".sylx-xiangxi").height()+top;
            var myTop=$(".syBanner").offset().top;
            var maxHeight=documentHeight-myTop;
            var scrollTop=$(document).scrollTop();
            if(scrollTop+fixHeight>documentHeight-maxHeight){
                $(".sylx-xiangxi").css("position","absolute");
            }else{
                $(".sylx-xiangxi").css("position","fixed");
            }
        });

        $("#orderList li").bind("click",function(){
            $("#orderType").attr("data-order",$(this).attr("data-order"));
            $("#orderType").html("排序："+$(this).html());
            currentPage=1;
            searchTip();
        });

        $("#showHot").bind("click",function(){
            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }else{
                $(this).addClass("active");
            }
            currentPage=1;
            searchTip();
        });

        $("#typeList span").bind("click",function(){
            if($(this).hasClass('active')){
                $(this).removeClass('active');
            }else{
                $(this).addClass('active');
            }
            currentPage=1;
            searchTip();
        });


        $("img").lazyload({
            placeholder : "/assets/images/loading.gif", //加载图片前的占位图片
            effect      : "fadeIn"//, //加载图片使用的效果(淡入)
            //threshold   :500
        });
        //searchTip();
        //init page click
        $("#spage li a").bind("click",function(){
            currentPage=$(this).attr("page");
            searchTip();
        });
        $("#searchBtn").bind("click",function(){
            currentPage=1;
            searchTip();
        });
        $("#search").keypress(function(e){
            if(e.keyCode==13){
                $("#searchBtn").click();
            }
        });

        $("#add").bind("click",function(){
            var peopleCount=$("#peopleCount").val();
            if(Main.isNotEmpty(peopleCount)){
                try{
                    peopleCount=parseInt(peopleCount);
                }catch(e){ peopleCount=0 }
            }else{
                peopleCount=0;
            }
            peopleCount++;
            $("#peopleCount").val(peopleCount);
            searchTip();
        });
        $("#subtract").bind("click",function(){
            var peopleCount=$("#peopleCount").val();
            if(Main.isNotEmpty(peopleCount)){
                try{
                    peopleCount=parseInt(peopleCount);
                }catch(e){ peopleCount=0 }
            }else{
                peopleCount=0;
            }
            peopleCount--;
            if(peopleCount<=0){
                peopleCount="";
            }
            $("#peopleCount").val(peopleCount);

            searchTip();
        });
    });

    function scrollList()
    {
        var scroll_offset=$("#trip_base_list").offset();
        $("body,html").animate({
            scrollTop:0
        },800);
    }

    function searchTip(){
        var title=$("#search").val();
        var peopleCount=$("#peopleCount").val();
        var tagList="";
        var orderType= $("#orderType").attr("data-order");
        var hot="";
        var dataType=[];
        if($("#showHot").hasClass("active")){
            hot=1;
        }
        $("#tagList span[class='active']").each(function(){
            if(tagList==''){
                tagList+=$(this).html();
            }else{
                tagList+=',';
                tagList+=$(this).html();
            }
        });
        $("#typeList span[class='active']").each(function(){
            dataType.push($(this).attr("dataType"));
        });
        var amount=$("#amount").val();

        $.ajax({
            url :'/view-trip/get-trip-list',
            type:'post',
            data:{
                p:currentPage,
                title:title,
                peopleCount:peopleCount,
                tag:tagList,
                amount:amount,
                orderType:orderType,
                hot:hot,
                type:dataType,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend:function(){
                //show load
            },
            error:function(){
                //hide load
                Main.showTip("获取随游失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){

                    $("#trip_base_list").html("");
                    var list=data.data.result;
                    if(data.data.totalCount>100){
                        $("#searchResultCount").html("100+");
                    }else{
                        $("#searchResultCount").html(data.data.totalCount);
                    }
                    if(list.length==0){
                        $("#trip_base_list").html("<div style='min-height:400px;text-align: center;height: 200px;line-height: 200px;'>暂时没有找到相关随游</div>")
                        $("#spage").html("");
                        return;
                    }
                    scrollList();
                    var trip,html="";
                    for(var i=0;i<list.length;i++){
                        trip=list[i];
                        var title=trip.title;
                        var tripClass='';
                        var basePriceType='';
                        var commentCount=0;
                        if(title.length>20){
                            title=title.substring(0,20)+"...";
                        }
                        if((i+1)%2==0){
                            tripClass='nomg'
                        }
                        if(trip.basePriceType==TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT){
                            basePriceType='每次'
                        }else{
                            basePriceType='每人';
                        }
                        if(Main.isNotEmpty(trip.commentCount)){
                            commentCount=trip.commentCount
                        }

                        html+=' <div class="web-tuijian fl '+tripClass+'">';
                        html+='     <a href="'+UrlManager.getTripInfoUrl(trip.tripId)+'" class="pic">';
                        if(trip.isHot==1){
                            html+='         <span class="hot"></span>';
                        }
                        html+='         <img src="'+trip.titleImg+'" width="410" height="267">';
                        html+='         <p class="p4"><span>￥'+trip.basePrice+'</span>';
                        html+='             '+basePriceType;
                        html+='         </p>';
                        html+='     </a>';
                        html+='     <a target="_blank" href="/view-user/info?u='+trip.userSign+'" class="user"><img src="'+trip.headImg+'" ></a>';
                        html+='     <p class="title">'+title+'</p>';
                        html+='     <p class="xing">'

                        if(trip.score>=2){html+='<img src="/assets/images/start1.fw.png" alt="">';}else{html+='<img src="/assets/images/start2.fw.png" alt="">';}
                        if(trip.score>=4){html+='<img src="/assets/images/start1.fw.png" alt="">';}else{html+='<img src="/assets/images/start2.fw.png" alt="">';}
                        if(trip.score>=6){html+='<img src="/assets/images/start1.fw.png" alt="">';}else{html+='<img src="/assets/images/start2.fw.png" alt="">';}
                        if(trip.score>=8){html+='<img src="/assets/images/start1.fw.png" alt="">';}else{html+='<img src="/assets/images/start2.fw.png" alt="">';}
                        if(trip.score>=10){html+='<img src="/assets/images/start1.fw.png" alt="">';}else{html+='<img src="/assets/images/start2.fw.png" alt="">';}

                        html+='         <span>'+trip.tripCount+'人去过</span><span>'+commentCount+'条评论</span>';
                        html+='     </p>';
                        html+=' </div>';
                    }

                    $("#trip_base_list").html(html);
                    $("#spage").html(data.data.pageHtml);
                    $("#spage li a").bind("click",function(){
                        currentPage=$(this).attr("page");
                        searchTip();
                    });
                    $("img").lazyload({
                        placeholder : "/assets/images/loading.gif", //加载图片前的占位图片
                        effect      : "fadeIn" //加载图片使用的效果(淡入)
                    });

                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }

</script>
