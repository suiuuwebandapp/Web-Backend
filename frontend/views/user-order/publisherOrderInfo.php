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

<div class="sy_serverDetail">
    <h2 class="title"><a href="<?=\common\components\SiteUrl::getTripUrl($orderInfo->tripId)?>" target="_blank"><?=$tripJsonInfo['info']['title']?></a></h2>
    <p class="p1">订单创建时间：<span style="margin-right: 40px"><?=date('Y年m月d日 H:i',strtotime($orderInfo->createTime))?></span>订单号 :<span> <?=$orderInfo->orderNumber?></span></p>
    <dl class="list clearfix">
        <dt>
            <span class="span1">游客</span>
            <span class="span2">联系方式</span>
            <span class="span3">订单状态</span>
        </dt>
        <dd class="user">
            <span class="span1">
                <a href="<?=\common\components\SiteUrl::getViewUserUrl($userInfo->userSign)?>" class="usePic" target="_blank"><img src="<?=$userInfo->headImg?>"></a>
                <b class="userName"><?=$userInfo->nickname?></b>
                <a href="javascript:;" onclick="Main.showSendMessage('<?=$userInfo->userSign?>')" class="email"></a>
            </span>
            <span class="span2"><?=$userInfo->areaCode?> <?=$userInfo->phone?></span>
            <span class="span3"><a href="javascript:;" class="btn"><?=\common\entity\UserOrderInfo::getOrderStatusDes($orderInfo->status); ?></a></span>
        </dd>
    </dl>
    <?php
        $serviceList=json_decode($orderInfo->serviceInfo,true);
    ?>
    <?php if(!empty($serviceList)){ ?>
        <?php if(isset($tripJsonInfo['info']['type'])&&$tripJsonInfo['info']['type']==\common\entity\TravelTrip::TRAVEL_TRIP_TYPE_TRAFFIC){ ?>
            <dl class="list list02 clearfix">
                <dt>
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
                <dt>
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
    <p class="title02">收取服务款</p>
    <p class="text">服务完成后，请您提醒用户在<a href="/user-info?tab=myOrderManager"><span>个人中心-我的预订</span></a>中完成相应服务的确认并完成评价，之后您就可以在个人账户中查询到服务所得的收入了。</p>
    <p class="text">您可以随时通过<a href="/user-info?tab=userAccount"><span>个人中心 - 个人账户</span></a>将您的账户内金额转至支付宝,财付通。 其他提款方式请联系客服。</p>

    <p class="title02">客户支持</p>
    <p class="text">如果需要预订方面的帮助，或者遇到紧急情况，我们都将随时为您提供解决方案！</p>
    <p class="text">电话:010 5848 3692</p>
    <p class="text">客服微信：Chipmunkfoxy</p>
    <p class="text">客服QQ：  1295913524</p>
    <div class="btns">
        <a href="/user-info?tab=tripManager" class="btn seen">查看全部订单</a>
        <?php if($orderInfo->status==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){ ?>
            <a href="javascript:;" id="cancelOrder" class="btn cancel">取消订单</a>
        <?php } ?>
    </div>
</div>


<script type="text/javascript">


    $(document).ready(function(){
        $("#cancelOrder").bind("click",function(){
            var orderId=$('#orderId').val();
            showCancelWindow(orderId);
        });
    });
    /**
     * 填出取消订单窗口
     * @param orderId
     */
    function showCancelWindow(orderId){
        if(orderId==''){
            Main.showTip("无效的订单");
            return;
        }
        $("#show_message_cancel_order_id").val(orderId);
        $("#show_order_message").html("");
        $("#showOrderDiv").show();
        $("#myMask").show();
    }
    /**
     * 随友取消订单
     * @param orderId
     */
    function publisherCancelOrder(){
        if(!confirm("确定取消订单吗？")){
            return;
        }
        var orderId=$("#show_message_cancel_order_id").val();
        var message=$("#show_order_message").val();
        if(orderId==''){
            Main.showTip("无效的订单");
            return;
        }
        if(message==''){
            Main.showTip("请输入退款原因");
            return;
        }
        $.ajax({
            url: "/user-order/publisher-cancel-order",
            type: "post",
            data:{
                orderId:orderId,
                message:message,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip('取消订单异常');
            },
            success: function(data){
                var result= $.parseJSON(data);
                if(result.status==1){
                    Main.showTip('取消订单成功');
                    Main.changeLocationUrl(window.location.href);
                }else{
                    Main.showTip('取消订单异常');
                }
            }
        });
    }

</script>