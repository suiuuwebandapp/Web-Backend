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
        background-color: #EEEEEE;
    }
</style>


<script type="text/javascript" src="/assets/js/UI/jquery-ui.js"></script>

<!--sylx-->
<div class="sylx w1200">
    <div class="sylx-serch">
        <input type="text" value="" class="w285" id="search">
        <input type="button" value="搜索" class="w52" id="searchBtn">
    </div>
    <div class="sylx-xiangxi clearfix">
        <p class="p1 clearfix">
            <label>成员:</label><a href="#" class="icon jian"></a>
            <input type="text" id="peopleCount"><a href="#" class="icon add"></a>
        </p>
        <p class="p2 clearfix" id="tagList"><label>类型:</label>
            <span class="active">全部</span>
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

        </ul>
        <ol id="spage">
        </ol>
    </div>
    <h2 class="title">热门推荐</h2>
    <div class="sylx-list h400">
        <ul>
            <?php foreach($rTravel as $trip){?>
            <li>
                <a href="/view-trip/info?trip=<?php echo $trip['tripId'];?>"><img src="<?php echo $trip['titleImg'];?>" alt=""></a>
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
                    <font>总价:<b><?php echo $trip['basePrice'];?></b></font>

                </div>
            </li>
            <?php }?>
        </ul>
    </div>
</div>
<!--sylx-->

i<script type="text/javascript">
    var currentPage=1;

    /*-----随游-价格区间拖动条----*/
    $(function() {
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 10000,
            values: [ 0, 10000 ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "￥" + ui.values[ 0 ] + " - ￥" + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "￥" + $( "#slider-range" ).slider( "values", 0 ) +
        " - ￥" + $( "#slider-range" ).slider( "values", 1 ) );
    });

    $(document).ready(function(){
        initSearchInfo();
        searchTip();
        $("#searchBtn").bind("click",function(){
            currentPage=1;
            searchTip();
        });

    });

    function initSearchInfo(){
        var href=window.location.href;
        var searchKey="#~search=";
        if(href.indexOf(searchKey)==-1){
            return;
        }
        var searchInfo=href.substring(href.indexOf(searchKey)+searchKey.length,href.length);
        $("#search").val(searchInfo);


    }



    function searchTip(){
        var title=$("#search").val();
        var peopleCount=$("#peopleCount").val();
        var tag=$("#tagList span[class='active']").html();
        var amount=$("#amount").val();

        $.ajax({
            url :'/view-trip/get-trip-list',
            type:'post',
            data:{
                p:currentPage,
                title:title,
                peopleCount:peopleCount,
                tag:tag,
                amount:amount,
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
                        '<a href="/view-trip/info?trip='+trip.tripId+'"><img src="'+trip.titleImg+'" alt=""></a>' +
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

                        html+='</p><font>总价:<b>'+trip.basePrice+'</b></font></div></li>';
                    }
                    $("#trip_base_list ul").html(html);
                    $("#spage").html(data.data.pageHtml);
                    $("#spage li a").bind("click",function(){
                        currentPage=$(this).attr("page");
                        searchTip();
                    });

                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }

</script>
