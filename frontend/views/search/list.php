<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/13
 * Time : 上午10:50
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<div class="serchTop">
    <div class="serch">
        <input type="text" value="<?=$search?>" id="search" class="text1">
        <input type="button" value="搜索" class="btn1" id="btnSearch">
    </div>
</div>

<div class="sosoCon w1200">
    <ul class="nav con-nav" id="ul_tab">
        <li><a href="javascript:;" class="active">随游</a></li>
        <li><a href="javascript:;">专栏</a></li>
    </ul>
    <!--list开始-->
    <div id="trip_div" class="slist w1200 TabCon clearfix" style="display:block;">
        <ul id="ul1">

        </ul>
        <a href="javascript:;" class="btn8" id="showTripMore">显示更多</a>

    </div>
    <div id="article_div" class="szhuan w1200 TabCon clearfix">
        <ul style="overflow: hidden">
            <li>
                <img alt="" src="images/zl2.fw.png">
                <div>
                    <h4>第十一卷</h4>
                    <p>日本京都奈良公园一日游</p>
                </div>
            </li>
        </ul>
        <a href="javascript:;" class="btn8" id="showArticleMore">显示更多</a>
    </div>

    <!--list结束-->
</div>


<script type="text/javascript">

    var currentTripPage=1;
    var currentArticlePage=1;

    $(document).ready(function(){
        getSearchList();

        $("#btnSearch").bind("click",function(){
            currentArticlePage=1;
            currentTripPage=1;
            getSearchList();
        });


        $("#showTripMore").bind("click",function(){

            getSearchList();
        });
        $("#showArticleMore").bind("click",function(){
            getSearchList();
        });

        $("#ul_tab li").eq(0).bind("click",function(){
            $("#trip_div").show();
            $("#article_div").hide();
            $("#ul_tab li").eq(0).find("a").addClass("active");
            $("#ul_tab li").eq(1).find("a").removeClass("active");
        });
        $("#ul_tab li").eq(1).bind("click",function(){
            $("#trip_div").hide();
            $("#article_div").show();
            $("#ul_tab li").eq(1).find("a").addClass("active");
            $("#ul_tab li").eq(0).find("a").removeClass("active");
        });
    });


    function getSearchList()
    {
        var search=$("#search").val();
        if(search==''){
            Main.showTip("请输入您想要搜索的内容");
            return;
        }

        $.ajax({
            url :'/search/search',
            type:'post',
            data:{
                s:search,
                tp:currentTripPage,
                ap:currentArticlePage,
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
                    var rst=data.data.tripResult;
                    var keywords=data.data.words;
                    buildTripHtml(rst,keywords);
                }else{
                    Main.showTip("搜索失败");
                }
            }
        });

    }

    function buildTripHtml(tripResult,keywords)
    {
        var trip,html="";
        var list=tripResult.result;
        for(var i=0;i<list.length;i++){
            trip=list[i];
            var title=trip.title;
            var intro=trip.intro;
            if(title.length>13){
                title=title.substring(0,13)+"...";
            }
            for(var j=0;j<keywords.length;j++){
                title=title.replaceAll(keywords[j],"<b style='color: red'>"+keywords[j]+"</b>");
                intro=intro.replaceAll(keywords[j],"<b style='color: red'>"+keywords[j]+"</b>");

            }

            html+='<li><div class="box"><img src="'+trip.titleImg+'" alt="" width="284px" height="260px"></div>';
            html+='<a href="/view-trip/info?trip='+trip.tripId+'"><div class="zhezhao">';
            html+='<p>'+intro+'</p>';
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
        if(currentTripPage==1){
            $("#ul1").html(html);
        }else{
            $("#ul1").append(html);
        }

        if(tripResult.totalPage==currentTripPage){
            $("#showTripMore").html("暂无更多");
            $("#showTripMore").unbind("click");
            return;
        }
        currentTripPage++;
    }

</script>