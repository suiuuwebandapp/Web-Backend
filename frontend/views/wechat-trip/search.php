


    <link rel="stylesheet" href="/assets/other/weixin/css/nouislider.css">
    <script type="text/javascript" src="/assets/other/weixin/js/nouislider.min.js"></script>


    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">筛选搜索</p>
    </div>
<div class="con w_suiyouSelect clearfix">
    <form action="/wechat-trip/select-list?str=<?php echo $str;?>" method="post" id="search_id">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
        <div class="search_out clearfix">
            <div class="search fl">
                <input type="text" placeholder="输入你感兴趣的地点" value="<?= $str?>" id="site">
                <a href="javascript:;" class="searchIcon" onclick="submitSearch()"><img src="/assets/other/weixin/images/top-search.png"></a>
            </div>
            <input type="button" value="取消" class="cancel fr" onclick="javascript:history.go(-1);">

        </div>
        <p>分类</p>
        <div class="type clearfix" id="typeSpan">
            <span type="1" >慢性探索</span><span type="2" >个性玩法</span><span type="3">交通服务</span>
        </div>
    <p>出游人数</p>
    <div class="row">
        <a href="javascript:;" class="minus" onclick="numberChage(0)" ></a>
        <input type="text" class="text" value="<?php echo empty($peopleCount)?0:$peopleCount ;?>" id="number" name="peopleCount">
        <a href="javascript:;" class="add" onclick="numberChage(1)"></a>
    </div>
    <p>类型</p>
    <div class="type clearfix" id="tagSpan">
        <span >家庭</span><span>购物</span><span>自然</span><span>惊险</span><span>浪漫</span><span>博物馆</span><span>猎奇</span>
    </div>
        <input id="tagList" name="tag" value="" hidden="hidden">
        <input id="typeList" name="type" value="" hidden="hidden">
        <input id="amount" name="amount" value="" hidden="hidden">
    <div class="price-select clearfix">
        <div id="money">
            <p id="p1">价格(元):&nbsp;&nbsp;</p>
            <div id="v2">￥0</div>
            <div id="v3">-</div>
            <div id="v1">￥1</div>
        </div>
        <div id="slider"></div>
        <!--价格区间滑块-->

    </div>
    <a href="javascript:;" class="btn"  onclick="submitSearch()">确定</a>
    </form>
</div>
    <script>
        var slider = document.getElementById('slider');
        var startPrice="<?php echo $startPrice;?>";
        var endPrice="<?php echo $endPrice;?>";
        noUiSlider.create(slider, {
            start: [startPrice, endPrice],
            connect: true,
            range: {
                'min': 0,
                'max': 10000
            },
            step: 1
        });
        var valueInput = document.getElementById('v1'),
            valueSpan = document.getElementById('v2');

        // When the slider value changes, update the input and span
        slider.noUiSlider.on('update', function( values, handle ) {
            if ( handle ) {
                valueInput.innerHTML = "￥"+Math.round(values[handle]);
            } else {
                valueSpan.innerHTML = "￥"+Math.round(values[handle]);
            }
        });

        // When the input changes, set the slider value
        valueInput.addEventListener('change', function(){
            slider.noUiSlider.set([null, this.value]);
        });
    </script>
<script>
    function submitSearch()
    {
        var tagList="";
        var typeList="";
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
        $("#typeSpan span[class='active']").each(function(){
            if(typeList=='')
            {
                typeList+=$(this).attr('type');
            }else
            {
                typeList+=',';
                typeList+=$(this).attr('type');
            }
        });

        $("#amount").val($("#v2").html()+" - "+$("#v1").html());
        $("#tagList").val(tagList);
        $("#typeList").val(typeList);
        var site=$('#site').val();
        $('#search_id').attr("action",("/wechat-trip/select-list?str="+site));
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
    $("#typeSpan span").bind("click",function(){
        if( $(this).attr('class')=="active"){
            $(this).attr('class',"");
        }else{
            $(this).attr('class',"active");
        }
    });
</script>
