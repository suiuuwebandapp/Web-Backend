<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游更多筛选</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery-ui.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-ui.js"></script>
    <script type="text/javascript">

        /*-----随游-价格区间拖动条----*/
        $(function() {
            var startPrice="<?php echo $startPrice;?>";
            var endPrice="<?php echo $endPrice;?>";
            $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 10000,
                values: [ startPrice, endPrice ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "￥" + ui.values[ 0 ] + " - ￥" + ui.values[ 1 ] );
                }
            });
            var tag="<?php echo $tag;?>";
            $("#tagSpan span").each(function(){
                if(tag.indexOf($(this).html())>=0)
                {
                    $(this).attr("class","active");
                }
            });
            $( "#amount" ).val( "￥" + $( "#slider-range" ).slider( "values", 0 ) +
            " - ￥" + $( "#slider-range" ).slider( "values", 1 ) );
        });


    </script>

</head>

<body>
<div class="con w_suiyouSelect clearfix">
    <form action="/wechat-trip/select-list?str=<?php echo $str;?>" method="post" id="search_id">
    <p>出游人数</p>
    <div class="row">
        <a href="javascript:;" class="minus" onclick="numberChage(0)" ></a>
        <input type="text" class="text" value="<?php echo $peopleCount ;?>" id="number" name="peopleCount">
        <a href="javascript:;" class="add" onclick="numberChage(1)"></a>
    </div>
    <p>类型</p>
    <div class="type clearfix" id="tagSpan">
        <span >家庭</span><span>购物</span><span>自然</span><span>惊险</span><span>浪漫</span><span>博物馆</span><span>猎奇</span>
    </div>
        <input id="tagList" name="tag" value="" hidden="hidden">
    <div class="price-select clearfix">
        <p>
            <label for="amount">价格:</label>
            <input type="text" id="amount" name="amount">
        </p>
        <div id="slider-range"></div>
    </div>
    <a href="javascript:;" class="btn"  onclick="submit()">确定</a>
    </form>
</div>

<script>
    function submit()
    {
        var tagList="";
        $("#tagSpan span[class='active']").each(function(){
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
        $("#tagList").val(tagList);
        $('#search_id').submit();
    }
   function numberChage(i)
   {
       var count=  $('#number').val()
       if(i==1)
       {
           count++;
       }else
       {
           count--;
           if(count<1)
           {
               count=0;
           }
       }
       $('#number').val(count);
   }

    $("#tagSpan span").bind("click",function(){
        if( $(this).attr('class')=="active"){
            $(this).attr('class',"");
        }else{
        $(this).attr('class',"active");
        }
    });
</script>
</body>
</html>