

    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>
    <style>
        .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
        .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
        .active{color: #97CBFF}
    </style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <ul class="oderNav">
            <li><a id="sort1" href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount=<?=$amount;?>&type=<?=$type;?>&activity=<?=$activity;?>&sort=1">推荐分数</a></li>
            <li><a id="sort2" href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount=<?=$amount;?>&type=<?=$type;?>&activity=<?=$activity;?>&sort=2">预定数</a></li>
            <li><a id="sort3" href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount=<?=$amount;?>&type=<?=$type;?>&activity=<?=$activity;?>&sort=3">评论数</a></li>
        </ul>
        <a href='/wechat-trip/search?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount=<?=$amount;?>&type=<?=$type;?>&sort=<?=$sort;?>' class="searchBtn"></a>
    </div>
    <div class="con w_suiyou">

    <?php if(count($list)==0){?>
        <img src="/assets/other/weixin/images/logo02.png" class="logo">
        <p class="noOrder">没有合适的随游哦</p>
    <?php }else{?>

    <div class="content" id="tripList">
        <?php foreach($list as $val){
            ?>
        <div class="box min-height">
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
        <?php }}?>
</div>
</div>
<script>
    var tag="<?php echo $tag;?>";
    $("#tagLi li").each(function(){
        if(tag.indexOf($(this).html())>=0)
        {
            $(this).attr("class","active");
        }
    });
    var active="<?php echo $activity;?>";

    var sort='<?php echo $sort;?>';
    if(sort==2)
    {
        $("#sort2").attr("class","active");
    }else if(sort==3)
    { $("#sort3").attr("class","active");
    }else
    { $("#sort1").attr("class","active");}
    var page="<?php echo $c;?>";
    $(window).scroll(function(){
        　　var scrollTop = $(this).scrollTop();
　　var scrollHeight = $(document).height();
　　var windowHeight = $(this).height();
　　if(scrollTop + windowHeight == scrollHeight){
            page++;
            var str='<?php echo $str;?>';

            $.ajax({
                url:"/wechat-trip/select-list?str="+str+"&sort="+sort+"&activity="+active,
                type:'post',
                data:{
                    _csrf: $('input[name="_csrf"]').val(),
                    tag:"<?php echo $tag;?>",
                    peopleCount:"<?php echo $peopleCount;?>",
                    amount:"<?php echo $amount;?>",
                    page:page,
                    ajax:'true',
                    type:'<?= $type?>',
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
                        if(listCount==0)
                        {
                            page--;
                        }
                        var html="";
                        for(var i=0;i<listCount;i++)
                        {
                            html+='<div class="box min-height">';
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
                            $('#tripList').append(html);
                        }
                    }else{
                        alert('加载失败');
                    }
                }
            });
　　}
    });
</script>
