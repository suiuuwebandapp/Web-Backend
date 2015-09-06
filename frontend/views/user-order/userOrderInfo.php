<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/2
 * Time : 下午2:34
 * Email: zhangxinmailvip@foxmail.com
 */
?>


<input id="orderId" type="hidden" value="<?=$orderInfo->orderId?>"/>
<div class="yk_serverDetail">
    <div class="user">
        <div class="left">
            <a href="<?=\common\components\SiteUrl::getViewUserUrl($userInfo->userSign)?>" class="usePic" target="_blank"><img src="<?=$userInfo->headImg?>"></a>
            <b class="userName"><?=$userInfo->nickname?></b>
            <a href="javascript:;" onclick="Main.showSendMessage('<?=$userInfo->userSign?>')" class="email"></a>
            <p class="phone"><?=$userInfo->areaCode?> <?=$userInfo->phone?></p>
        </div>
        <div class="right">
            <a href="<?=\common\components\SiteUrl::getTripUrl($orderInfo->tripId)?>" target="_blank"><h2 class="title" style="color: #ffffff"><?=$tripJsonInfo['info']['title']?></h2></a>
            <p>订单创建时间：<span><?=date('Y年m月d日 H:i',strtotime($orderInfo->createTime))?></span>
                订单号 :<span> <?=$orderInfo->orderNumber?></span>
            </p>
            <a href="javascript:;" class="btn"><?=\common\entity\UserOrderInfo::getOrderStatusDes($orderInfo->status); ?></a>
        </div>
        <span class="span1">
        </span>

    </div>
    <?php
        $serviceList=json_decode($orderInfo->serviceInfo,true);
    ?>
    <?php if(!empty($serviceList)){ ?>
        <?php if(isset($tripJsonInfo['info']['type'])&&$tripJsonInfo['info']['type']==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){ ?>
            <dl class="list list02 clearfix">
                <dt class="dt01">
                    <span class="span1">服务日期</span>
                    <span class="span2">服务项目</span>
                    <span class="span3">出行人数</span>
                </dt>
                <?php foreach($serviceList as $service){ ?>
                    <dd>
                        <span class="span1"><?=date('Y年m月d日 H:i',strtotime($service['date'].' '.$service['time']))?></span>
                        <span class="span2">
                            <?php if($service['type']=='car'){echo '包车';}else if($service['type']=='airplane_send'){echo '送机';}else{echo '接机';};?>
                            ￥<?=$service['price']?> <?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?'每次':'每人';?>
                        </span>
                        <span class="span3"><?=$service['person']?>人</span>
                    </dd>
                <?php } ?>
            </dl>

        <?php }else{ ?>
            <dl class="list list02 clearfix">
                <dt class="dt01">
                    <span class="span1">服务日期</span>
                    <span class="span2">服务项目</span>
                    <span class="span3">出行人数</span>
                </dt>
                <?php foreach($serviceList as $service){ ?>
                    <dd>
                        <span class="span1"><?=date('Y年m月d日 H:i',strtotime($orderInfo->beginDate.' '.$orderInfo->startTime))?></span>
                        <span class="span2">
                            <?=$service['title']?> ￥<?=$service['money']?>
                        </span>
                        <span class="span3"><?=$orderInfo->personCount?>人</span>
                    </dd>
                <?php } ?>
            </dl>

        <?php } ?>
    <?php } ?>
    <p class="allMoney">总价￥<?=intval($orderInfo->totalPrice)?></p>
    <?php if(isset($contact)&&(!empty($contact->arriveFlayNumber)&&!empty($contact->leaveFlayNumber))){ ?>
        <dl class="list list02 clearfix">
            <dt>接机服务信息 </dt>
            <dd>
                <span class="span1">到达航班</span>
                <span class="span2">离开航班</span>
                <span class="span3">接机目的地</span>
            </dd>
            <dd>
                <span class="span1"><?=isset($contact->arriveFlayNumber)?$contact->arriveFlayNumber:'';?></span>
                <span class="span2"><?=isset($contact->leaveFlayNumber)?$contact->leaveFlayNumber:'';?></span>
                <span class="span3"><?=isset($contact->destination)?$contact->destination:'';?></span>
            </dd>
        </dl>
    <?php }?>
    <p class="title02">退订政策</p>
    <p class="text"> 1.支付并提交订单后48小时无人接单，则订单自动取消，全额返还服务费</p>
    <p class="text"> 2.订单提交时间未满48小时，但超过订单预期服务时间的，全额返还服务费</p>
    <p class="text"> 3.在订单被接单之前取消订单，全额返还所支付费用</p>
    <p class="text"> 4.所提交订单被随友接单，在服务指定日期前5天可以申请取消预订并全额退款</p>
    <p class="text"> 5.在指定日期内5天可以申请退款，经平台审核后返还部分预订费用。在随游服务过程中及服务后且未确认完成服务前，可以
        提交退款请求，经平台调查审核后返还部分服务费用。</p>

    <p class="title02">客户支持</p>
    <p class="text">如果需要预订方面的帮助，或者遇到紧急情况，我们都将随时为您提供解决方案！</p>
    <p class="text">电话:010 5848 3692</p>
    <p class="text">客服微信：Chipmunkfoxy</p>
    <p class="text">客服QQ：  1295913524</p>
    <div class="btns">
        <a href="/user-info?tab=myOrderManager" class="btn seen">查看全部订单</a>
        <?php if($orderInfo->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){ ?>
            <a href="javascript:;" id="cancelOrderBtn" class="btn cancel">取消订单</a>
            <a href="<?=\common\components\SiteUrl::getOrderPayUrl($orderInfo->orderNumber)?>" class="btn cancel">去支付</a>
        <?php }else if(\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){ ?>

        <?php } ?>
    </div>


</div>



<script type="text/javascript">

    $(document).ready(function(){
        $("#cancelOrderBtn").bind('click',function(){
            var orderId=$("#orderId").val();
            cancelOrder(orderId);
        });
    });

    /**
     * 用户取消订单
     * @param orderId
     */
    function cancelOrder(orderId){
        if(!confirm("确定取消订单吗？")){
            return;
        }
        if(orderId==''){
            Main.showTip("无效的订单");
            return;
        }
        $.ajax({
            url: "/user-order/cancel-order",
            type: "post",
            data:{
                orderId:orderId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip('取消订单异常');
            },
            success: function(data){
                var result=eval("("+data+")");
                if(result.status==1){
                    Main.showTip('取消订单成功');
                    window.location.href=window.location.href;
                }else{
                    Main.showTip('取消订单异常');
                }
            }
        });
    }

</script>