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
            <li>
                <a href="suiyou_xiangqing.html"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
        </ul>
        <div class="sylx-page">
            <ul>
                <li><a href="javascript:;">首页</a></li>
                <li><a href="javascript:;">1</a></li>
                <li><a href="javascript:;">2</a></li>
                <li><a href="javascript:;">3</a></li>
                <li><a href="javascript:;">4</a></li>
                <li><a href="javascript:;">5</a></li>
                <li><a href="javascript:;">6</a></li>
                <li><a href="javascript:;">尾页</a></li>
            </ul>
        </div>
    </div>
    <h2 class="title">类似推荐</h2>
    <div class="sylx-list h400">
        <ul>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
        </ul>
    </div>
    <h2 class="title">热门推荐</h2>
    <div class="sylx-list h400">
        <ul>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/a1.fw.png" alt=""></a>
                <p class="posi"><img src="/assets/images/1.png" alt=""><span>xiaolehuo</span></p>
                <div>
                    <h4>日本京都奈良公园一日游</h4>
                    <p>评论&nbsp;<span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start2.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                        <span><img src="/assets/images/start1.fw.png" alt=""></span>
                    </p>
                    <font>总价:<b>800</b></font>

                </div>
            </li>
        </ul>
    </div>
</div>
<!--sylx-->


<script type="text/javascript">


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
        searchTip();
        $("#searchBtn").bind("click",function(){
            searchTip();
        });
    });



    function searchTip(){
        var title=$("#search").val();
        var peopleCount=$("#peopleCount").val();
        var tag=$("#tagList span[class='active']").html();
        var amount=$("#amount").val();

        $.ajax({
            url :'/view-trip/get-trip-list',
            type:'post',
            data:{
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
                    var list=data.data;
                    if(list.length==0){
                        return;
                    }
                    var trip,html="";
                    for(var i=0;i<list.length;i++){
                        trip=list[i];
                        html+='<li>' +
                        '<a href="/view-trip/info?trip='+trip.tripId+'"><img src="'+trip.titleImg+'" alt=""></a>' +
                        '<p class="posi"><img src="'+trip.headImg+'" alt=""><span>'+trip.nickname+'</span></p>' +
                        '<div><h4>'+trip.title+'</h4><p>评论&nbsp;';
                        if(trip.headImg>=2){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.headImg>=4){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.headImg>=6){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.headImg>=8){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }
                        if(trip.headImg>=10){
                            html+='<span><img src="/assets/images/start1.fw.png" alt=""></span>';
                        }else{
                            html+='<span><img src="/assets/images/start2.fw.png" alt=""></span>';
                        }


                        html+='</p><font>总价:<b>'+trip.basePrice+'</b></font></div></li>';

                    }
                    $("#trip_base_list ul").append(html);
                }else{
                    Main.showTip("获取随游失败");
                }
            }
        });
    }

</script>
