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
<style>
    body{
        background-color: #F7F7F7;
    }
</style>


<script type="text/javascript" src="/assets/js/UI/jquery-ui.js"></script>
<script type="text/javascript" src="/assets/js/jquery.lazyload.min.js"></script>

<!--sylx-->
<div class="sylx w1200">
    <div class="sylx-serch">
        <input type="text" value="<?=empty($search)?"":$search?>" class="w285" id="search">
        <input type="button" value="搜索" class="w52" id="searchBtn">
    </div>
    <div class="sylx-xiangxi clearfix">
        <p class="p1 clearfix">
            <label>成员:</label>
            <a href="javascript:;" class="icon jian" id="subtract"></a>
            <input type="text" id="peopleCount">
            <a href="javascript:;" class="icon add" id="add"></a>
        </p>
        <p class="p2 clearfix" id="tagList"><label>类型:</label>

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
    <div class="sylx-list" id="trip_base_list">
        <ul>
            <?php if($pageResult->result!=null&&count($pageResult->result)>0){ ?>
                <?php foreach($pageResult->result as $trip){?>
                    <li>
                        <a href="/view-trip/info?trip=<?=$trip['tripId']?>">
                            <img src="/assets/images/grey.jpg" data-original="<?=$trip['titleImg']?>" >
                        </a>
                        <p class="posi">
                            <img src="<?=$trip['headImg'] ?>" alt="">
                            <span><?=$trip['nickname']?></span>
                        </p>
                        <div>
                            <h4><?=mb_strlen($trip['title'],"UTF-8")>15?mb_substr($trip['title'],0,15,"UTF-8")."...":$trip['title'] ?></h4>
                            <p>评论&nbsp;
                                <span><img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt=""></span>
                                <span><img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt=""></span>
                                <span><img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt=""></span
                                <span><img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt=""></span>
                                <span><img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt=""></span>
                            </p><font>基础价格：<b><?= $trip['basePrice'] ?></b></font>
                        </div>
                    </li>
                <?php } ?>
            <?php }?>
        </ul>
        <ol id="spage">
            <?= $pageResult->pageHtml?>
        </ol>
    </div>
    <h2 class="title">热门推荐</h2>
    <div class="sylx-list h400">
        <ul>
            <?php foreach($rTravel as $trip){?>
            <li>
                <a href="/view-trip/info?trip=<?php echo $trip['tripId'];?>">
                    <img src="/assets/images/grey.jpg" data-original="<?=$trip['titleImg']?>" >
                </a>
                <p class="posi"><img src="<?php echo $trip['headImg'];?>" alt=""><span><?php echo $trip['nickname'];?></span></p>
                <div>
                    <h4><?php echo $trip['title'];?></h4>
                    <p>评论&nbsp;
                        <?php for($i=0;$i<5;$i++){
                            $n=intval($trip['score']/2);
                            if($n<=$i)
                            {
                                echo '<span><img src="/assets/images/start2.fw.png" alt=""></span>';

                            }else{
                                echo '<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                            }
                        }?>
                    </p>
                    <font>基础价格：<b><?php echo $trip['basePrice'];?></b></font>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
</div>
<!--sylx-->

i<script type="text/javascript">
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
        $("img").lazyload({
            placeholder : "/assets/images/grey.jpg", //加载图片前的占位图片
            effect      : "fadeIn" //加载图片使用的效果(淡入)
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
        $("#tagList span[class='active']").each(function(){
            if(tagList=='')
            {
                tagList+=$(this).html();
            }else
            {
                tagList+=',';
                tagList+=$(this).html();
            }
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
                    scrollList();
                    $("#trip_base_list ul").html("");
                    var list=data.data.result;
                    if(list.length==0){
                        $("#trip_base_list ul").html("<div style='text-align: center;height: 200px;line-height: 200px;'>暂时没有找到相关随游</div>")
                        $("#spage").html("");
                        return;
                    }
                    var trip,html="";
                    for(var i=0;i<list.length;i++){
                        trip=list[i];
                        var title=trip.title;
                        if(title.length>13){
                            title=title.substring(0,13)+"...";
                        }

                        html+='<li>' +
                        '<a href="/view-trip/info?trip='+trip.tripId+'"><img src="/assets/images/grey.jpg" data-original="'+trip.titleImg+'" alt=""></a>' +
                        '<p class="posi"><img src="'+trip.headImg+'" alt=""><span>'+trip.nickname+'</span></p>' +
                        '<div><h4>'+title+'</h4><p>评论&nbsp;';
                        if(trip.score>=2){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.score>=4){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.score>=6){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.score>=8){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.score>=10){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }

                        html+='</p><font>基础价格：<b>'+trip.basePrice+'</b></font></div></li>';
                    }
                    $("#trip_base_list ul").html(html);
                    $("#spage").html(data.data.pageHtml);
                    $("#spage li a").bind("click",function(){
                        currentPage=$(this).attr("page");
                        searchTip();
                    });
                    $("img").lazyload({
                        placeholder : "/assets/images/grey.jpg", //加载图片前的占位图片
                        effect      : "fadeIn" //加载图片使用的效果(淡入)
                    });

                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }

</script>
