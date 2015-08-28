<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link type="text/css" rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.core.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>

    <!-- Mobiscroll JS and CSS Includes -->
    <link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />
    <script src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>

    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            //覆盖的代码
            $.mobile.ajaxEnabled = false;
            $.mobile.hashListeningEnabled = false;
            //$.mobile.linkBindingEnabled = false;
        });
    </script>
    <script type="text/javascript">

        $(function () {
            var now = new Date(),
                year = now.getFullYear(),
                month = now.getMonth();
            // Mobiscroll Calendar initialization
            $('#timeList').mobiscroll().time({
                theme: 'mobiscroll',
                display: 'bottom',
                buttons: [],
                headerText: false
            });

            $('#dateList').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true
            });
        });
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
</head>

<body>
<div id="page" class="userCenter">
    <?php include "left.php"; ?>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">预定随游</p>
    </div>
    <input type="hidden" name="tripId" value="<?=$info['info']['tripId'];?>" id="tripId"/>
    <input type="hidden" name="serviceIds" id="serviceIds" />
    <div class="con w_dingdan clearfix">
        <p>出行人数</p>
        <div class="row">
            <a href="#" class="minus" onclick="numberChage(0)"></a>
            <input type="text" class="text" value="1" id="number">
            <a href="#" class="add" onclick="numberChage(1)"></a>
        </div>

        <div class="clearfix">
            <div class="lists">
                <label for="amount">出行日期</label>
                <input id="dateList" class="sdate" placeholder="请选择出行日期 ..." />
                <label for="amount">随游时间</label>
                <input id="timeList" class="sdate" placeholder="请选择随游时间 ..." />
                <label for="amount">单项服务</label>
            </div>
            <div class="severs clearfix" id="serviceLi">
                <?php if(count($info['serviceList'])==0){?>
                    <p>
                        暂无附加服务
                    </p>
                <?php }?>
                <?php for($i=0;$i<count($info['serviceList']);$i++){
                    $service=$info['serviceList'][$i];
                    ?>
                <input type="checkbox" id="check<?=$i?>"  serviceId="<?=$service['serviceId']?>" servicePrice="<?=$service['money']?>" serviceType="<?=$service['type']?>" >
                    <label style="float:none" for="check<?=$i?>"><?= $service['title']?></label>
                    <span ><b>￥<?=intval($service['money'])?></b></span>
                    <span><?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?'人':'次' ?></span>
                <?php }?>
            </div>
        </div>
        <div class="fixed">
            <span class="money fl">总价:<b class="colOrange" id="allPrice"> ￥<?php echo intval($info['info']['basePrice']);?></b></span>
            <a href="#" class="btn colWit bgOrange" id="addOrder">支付</a>
        </div>
    </div>
</div>
<script>
    var TripBasePriceType={
        'TRIP_BASE_PRICE_TYPE_PERSON':1,
        'TRIP_BASE_PRICE_TYPE_COUNT':2
    };
    <?php
        $stepPriceJson='';
        if(!empty($info['priceList'])){
            $stepPriceJson=json_encode($info['priceList']);
        }
    ?>
    var basePrice='<?=intval($info['info']['basePrice']);?>';
    var basePriceType='<?=$info['info']['basePriceType'];?>';
    var maxPeopleCount='<?=$info['info']['maxUserCount'];?>';
    var stepPriceJson='<?=$stepPriceJson;?>';
    var serviceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT;?>';
    var serviceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE;?>';
    var userPublisherId='<?= $this->context->userPublisherObj!=null?$this->context->userPublisherObj->userPublisherId:''?>';
    var userId='<?= $this->context->userObj!=null?$this->context->userObj->userSign:''?>';
    var isOwner='<?= $publisherId==$info['info']['createPublisherId']?true:false; ?>';
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
                count=1;
            }
        }
        $('#number').val(count);
        showPrice();
    }
    $("#serviceLi input[type='checkbox']").bind("click",function(){
        showPrice();
    });
    $("#number").bind("blur",function(){
        showPrice();
    });
    //显示价格
    function showPrice()
    {
        var peopleCount=$("#number").val();

        var allPrice=0;

        if(peopleCount==''||peopleCount==0){
            return;
        }

        peopleCount=parseInt(peopleCount);
        maxPeopleCount=parseInt(maxPeopleCount);

        if(peopleCount>maxPeopleCount){
           alert("这个随友最多之能接待"+maxPeopleCount+"个小伙伴呦~");
            return;
        }
        //判断有没有阶梯价格
        var stepPriceList=[];
        if(stepPriceJson!=''){
            stepPriceList=eval("("+stepPriceJson+")");
        }
        if(basePriceType==TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT){
            allPrice=basePrice;
        }else{
            var bo=true;
            if(stepPriceList.length>0){
                for(var i=0;i<stepPriceList.length;i++){
                    var stepPrice=stepPriceList[i];
                    if(peopleCount>=stepPrice['minCount']&&peopleCount<=stepPrice['maxCount']){
                        allPrice=parseInt(stepPrice['price'])*peopleCount;
                        bo=false;
                        break;
                    }
                }
            }else{
                allPrice=parseInt(basePrice)*peopleCount;
            }
            if(bo)
            {
                allPrice=parseInt(basePrice)*peopleCount;
            }
        }

        //判断有没有附加服务
        $("#serviceLi input[type='checkbox']").each(function(){
            var tempPrice=parseInt($(this).attr("servicePrice"));
            if($(this).is(":checked")){
                //如果TRUE每次 FALSE 每人
                if($(this).attr("serviceType")==serviceTypeCount){
                    allPrice+=tempPrice;
                }else{
                    allPrice+=(tempPrice*peopleCount);
                }
            }
        });
        allPrice=parseInt(allPrice);
        $("#allPrice").html(" ￥"+allPrice);
    }

    $("#addOrder").bind("click",function(){
        if(isOwner==1){
            Main.showTip("您无法购买自己的随游哦~");
            return;
        }
        var peopleCount=$("#number").val();
        var tripId=$("#tripId").val();
        var beginDate=$("#dateList").val();
        var startTime=$("#timeList").val();
        var serviceArr=[];
        var serviceIds='';


        if(userId==''){
            $("#denglu").click();
            //Main.showTip("登录后才能购买哦~！");
            return;
        }
        if(beginDate==''){
            alert("请选择您的出行日期");
            return;
        }
        if(peopleCount==''||peopleCount==0){
            alert("请输入你的出行人数");
            return;
        }
        peopleCount=parseInt(peopleCount);
        maxPeopleCount=parseInt(maxPeopleCount);

        if(peopleCount>maxPeopleCount){
            alert("这个随友最多之能接待"+maxPeopleCount+"个小伙伴呦~");
            return;
        }
        if(startTime==''){
            alert("请输入您的起始时间");
            return;
        }

        $("#serviceLi input[type='checkbox']").each(function(){
            if($(this).is(":checked")){
                serviceArr.push($(this).attr("serviceId"));
            }
        });
        if(serviceArr!=''&&serviceArr.length>0){
            serviceIds=serviceArr.join(",");
        }

        $("#serviceIds").val(serviceIds);

        $.ajax({
            url :'/wechat-trip/add-order',
            type:'post',
            data:{
                tripId:tripId,
                peopleCount:peopleCount,
                beginDate:beginDate,
                startTime:startTime,
                serviceIds:serviceIds

            },
            error:function(){
                alert("提交订单异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order-info?id="+data.data;
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    });
</script>

</body>
</html>
