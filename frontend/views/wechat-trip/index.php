
    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.SuperSlide.2.1.1.js"></script>
    <style>
        .banners li img{
            min-height:10.89rem;
        }
    </style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <div class="search_out" onclick="gotoSelect('')">
            <input type="text" placeholder="你的旅行目的地" class="search" readonly="readonly">
            <a href="javascript:;" class="btn"><img src="/assets/other/weixin/images/top-search.png"> </a>
        </div>

    </div>
    <div class="indexBanner">
        <!--banner开始-->
        <div class="bd">
            <ul class="banners">
                <li class="banner01  min-height" onclick="gotoUrl('/wechat-trip/select-list?activity=1')">
                    <p class="title">大手拉小手的旅行</p>
                    <img class="min-height" src="http://image.suiuu.com/suiuu_index/01.jpg">
                </li>
                <li class="banner01  min-height" onclick="gotoUrl('/wechat-trip/select-list?activity=2')">
                    <p class="title">梦中的情人节</p>
                    <img class="min-height" src="http://image.suiuu.com/suiuu_index/10.jpg">
                </li>
            </ul>
            <div class="hd"><ul></ul></div>
        </div>
        <!--banner结束-->
        <script type="text/javascript">
            $(document).ready(function(){


                $(".prev,.next").hover(function(){
                    $(this).stop(true,false).fadeTo("show",1);
                },function(){
                    $(this).stop(true,false).fadeTo("show",1);
                });
                $(".indexBanner").slide({
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
    </div>
    <div class="indexCon">
        <a href="/we-chat-order-list/order-view" class="btn">开始你的专属定制<img src="/assets/other/weixin/images/dz.png"></a>
        <div class="tabs">
            <ul class="top tabTitle clearfix">
                <li><a href="javascript:;" class="active" onclick="changeType(1)">慢性探索</a></li>
                <li><a href="javascript:;" onclick="changeType(2)">个性玩法</a></li>
                <li><a href="javascript:;" onclick="changeType(3)">交通服务</a></li>
            </ul>
        </div>
    <div class="down tabCon" style="display:block;" id="boxTrip1">
        <?php if(count($page)==0){?>
            <img src="/assets/other/weixin/images/logo02.png" class="logo">
            <p class="noOrder">没有合适的随游哦</p>
        <?php }else{?>

        <div class="content" id="tripList1">
            <?php foreach($page as $val){
                ?>
                <div class="box  min-height">
                    <a href="/wechat-trip/info?tripId=<?php echo $val['tripId']?>" class="pic"><img src="<?php echo $val['titleImg']?>"></a>
                    <div class="details">
                        <h3 class="title"><?php echo $val['title']?></h3>
                        <p class="line clearfix">
                            <b class="colOrange">￥<?php echo $val['basePrice']?></b>
                            <img src="<?= $val['score']>=2?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $val['score']>=4?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $val['score']>=6?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $val['score']>=8?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <img src="<?= $val['score']>=10?'/assets/other/weixin/images/xing02.png':'/assets/other/weixin/images/xing01.png'; ?>" width="13" height="13">
                            <span><?php echo $val['commentCount']?$val['commentCount']:0 ?>条评论</span>
                            <span><?php echo $val['tripCount']?$val['tripCount']:0 ?>人去过</span>
                        </p>
                    </div>
                </div>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <div class="down tabCon" id="boxTrip2">
        <div class="content" id="tripList2">
        </div>
    </div>
    <div class="down tabCon" id="boxTrip3">
        <div class="content" id="tripList3">
        </div>
    </div>
    </div>
<script>
    var type=1;
    var page=0;
    function changeType(t)
    {
        page=0;
        type=t;
        getList();
    }
    function gotoSelect(str)
    {
        if(str=='')
        {
            window.location.href="/wechat-trip/select";
            return;
        }
        window.location.href="/wechat-trip/select-list?str="+str;
    }
    function gotoUrl(url)
    {
        window.location.href=url;
    }

    $(window).scroll(function(){
        　var scrollTop = $(this).scrollTop();
　　      var scrollHeight = $(document).height();
　　      var windowHeight = $(this).height();
    　　if(scrollTop + windowHeight == scrollHeight){
                page++;
            getList();
    　　}
    });

    function getList()
    {

        var boxTrip="#boxTrip"+type;
        var tripList = '#tripList'+type;
        $.ajax({
            url:"/wechat-trip/select-list?",
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                page:page,
                ajax:'true',
                type:type,
                number:10
            },
            error:function(){
                //hide load
                alert('加载失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    var list=data.data;
                    var listCount=list.length;
                    var html="";

                    if(listCount==0)
                    {
                        page--;
                        if(page==0){
                        html+='<img src="/assets/other/weixin/images/logo02.png" class="logo">';
                        html+='<p class="noOrder">没有合适的随游哦</p>';
                        $(boxTrip).html(html);
                        }
                    }else{
                    for(var i=0;i<listCount;i++)
                    {
                        html+='<div class="box  min-height">';
                        html+='<a href="/wechat-trip/info?tripId='+list[i].tripId+'" class="pic"><img src="'+list[i].titleImg+'"></a>';
                        html+='<div class="details">';
                        html+='<h3 class="title">'+list[i].title+'</h3>';
                        html+='<p class="line clearfix">';
                        html+='<b class="colOrange">￥'+list[i].basePrice+'</b>';
                        list[i].score>=2?html+='<img src="/assets/other/weixin/images/xing02.png" width="13" height="13">':html+='<img src="/assets/other/weixin/images/xing01.png" width="13" height="13">';
                        list[i].score>=4?html+='<img src="/assets/other/weixin/images/xing02.png" width="13" height="13">':html+='<img src="/assets/other/weixin/images/xing01.png" width="13" height="13">';
                        list[i].score>=6?html+='<img src="/assets/other/weixin/images/xing02.png" width="13" height="13">':html+='<img src="/assets/other/weixin/images/xing01.png" width="13" height="13">';
                        list[i].score>=8?html+='<img src="/assets/other/weixin/images/xing02.png" width="13" height="13">':html+='<img src="/assets/other/weixin/images/xing01.png" width="13" height="13">';
                        list[i].score>=10?html+='<img src="/assets/other/weixin/images/xing02.png" width="13" height="13">':html+='<img src="/assets/other/weixin/images/xing01.png" width="13" height="13">';
                        html+='</p>';
                        html+='</div>';
                        html+='</div>';
                    }
                    if(html!="")
                    {
                        if(page==0){
                        $(tripList).html("");
                        }
                        $(tripList).append(html);
                    }
                    }
                }else{
                    alert('加载失败');
                }
            }
        });
    }
</script>