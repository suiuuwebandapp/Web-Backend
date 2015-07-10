<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>
    <style>
        .logo{ width:4.6rem;display:block; margin:0 auto; margin-top:6.0rem; }
        .noOrder{ line-height:1.5rem;margin-top:10px;text-align: center; }
        .active{color: #97CBFF}
    </style>
</head>

<body>
<div class="w_suiyou">
    <nav class="top"><span class="fl"><?php echo $str?></span><a href="/wechat-trip/select" class="btn fr"><img src="/assets/other/weixin/images/top-search.png" width="45" height="49"></a></nav>
    <div class="selects clearfix con-nav">
        <a href="javascript:;" class="a1">&nbsp;&nbsp;&nbsp;人数<i class="icon"></i></a>
        <a href="javascript:;" class="a2">类型<i class="icon"></i></a>
        <a href="javascript:;" class="a3">价格<i class="icon"></i>&nbsp;&nbsp;&nbsp;</a>
        <div class="props">
            <div class="syprop syprop01 TabCon">
                <ul class="sets">
                    <li class="titles bgBlue">人数</li>
                    <li onclick="pCount(1,this)"  class="<?php if($peopleCount==1){echo "active";}?>" >1</li>
                    <li onclick="pCount(2,this)"  class="<?php if($peopleCount==2){echo "active";}?>" >2</li>
                    <li onclick="pCount(3,this)"  class="<?php if($peopleCount==3){echo "active";}?>" >3</li>
                    <li onclick="pCount(4,this)"  class="<?php if($peopleCount==4){echo "active";}?>" >4</li>
                    <li onclick="pCount(5,this)"  class="<?php if($peopleCount==5){echo "active";}?>" >5</li>
                    <li onclick="pCount(6,this)"  class="<?php if($peopleCount==6){echo "active";}?>" >5人以上</li>
                </ul>
            </div>
            <div class="syprop syprop02 TabCon">
                <ul class="sets" id="tagLi">
                    <li class="titles bgBlue">类型</li>
                    <li onclick="tagHandle('家庭',this)">家庭</li>
                    <li onclick="tagHandle('美食',this)">美食</li>
                    <li onclick="tagHandle('购物',this)">购物</li>
                    <li onclick="tagHandle('自然',this)">自然</li>
                    <li onclick="tagHandle('惊险',this)">惊险</li>
                    <li onclick="tagHandle('浪漫',this)">浪漫</li>
                    <li onclick="tagHandle('博物馆',this)">博物馆</li>
                    <li onclick="tagHandle('猎奇',this)">猎奇</li>
                </ul>
            </div>
            <div class="syprop syprop03 TabCon">
                <ul class="sets">
                    <li class="titles bgBlue">价格</li>
                    <li onclick="momeyHandle('￥0 - 200',this)" class="<?php if($startPrice<=200){echo "active";}?>">￥0 - 200</li>
                    <li onclick="momeyHandle('￥201 - 500',this)" class="<?php if($startPrice<=500&&$startPrice>200){echo "active";}?>">￥201 - 500</li>
                    <li onclick="momeyHandle('￥501 - 1000',this)" class="<?php if($startPrice<=1000&&$startPrice>500){echo "active";}?>">￥501 - 1000</li>
                    <li onclick="momeyHandle('￥1001 - 2000',this)" class="<?php if($startPrice<=2000&&$startPrice>1000){echo "active";}?>">￥1001 - 2000</li>
                    <li onclick="momeyHandle('￥2001 - 20000',this)" class="<?php if($startPrice>2000){echo "active";}?>">2000以上</li>
                </ul>
            </div>
        </div>
    </div>
    <?php if(count($list)==0){?>
        <img src="/assets/other/weixin/images/logo02.png" class="logo">
        <p class="noOrder">没有合适的随游哦</p>
    <?php }else{?>

    <div class="content" id="tripList">
        <?php foreach($list as $val){?>
        <div class="box">
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

    function tagHandle(str,obj){
        if($(obj).attr('class')!="active")
        {
            if(tag!=""){
            tag+=",";
            }
            tag+=str;
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag="+tag+"&amount=<?=$amount;?>";
        }else{
            var arr = tag.split(',');
           var i = arr.indexOf(str);
            arr.splice(i,1);
            tag =  arr.join(',');
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag="+tag+"&amount=<?=$amount;?>";
        }
    }

    function momeyHandle(s,obj){
        if($(obj).attr('class')!="active")
        {
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount="+s;
        }else{
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount=<?=$peopleCount;?>&tag=<?=$tag;?>&amount=";
        }
    }




    function pCount(i,obj){
        if($(obj).attr('class')!="active")
        {
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount="+i+"&tag=<?=$tag;?>&amount=<?=$amount;?>";
        }else{
            window.location.href="/wechat-trip/select-list?str=<?php echo $str;?>&peopleCount="+0+"&tag=<?=$tag;?>&amount=<?=$amount;?>";
        }

    }
    var page="<?php echo $c;?>";
    $(window).scroll(function(){
        　　var scrollTop = $(this).scrollTop();
　　var scrollHeight = $(document).height();
　　var windowHeight = $(this).height();
　　if(scrollTop + windowHeight == scrollHeight){
            page++;
            var str='<?php echo $str;?>';
            $.ajax({
                url:"/wechat-trip/select-list?str="+str,
                type:'post',
                data:{
                    tag:"<?php echo $tag;?>",
                    peopleCount:"<?php echo $peopleCount;?>",
                    amount:"<?php echo $amount;?>",
                    page:page,
                    type:'post',
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
                            html+='<div class="box">';
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
</body>
</html>
