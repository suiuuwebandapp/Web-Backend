<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/myTab.js"></script>

    <script type="text/javascript">
        $(function() {
            $('div#menu').mmenu();
        });
    </script>
    <script>
        function showHtml()
        {
            $("#page").show();
            $("#loading").hide();
        }
    </script>
    <link rel="stylesheet" href="/assets/other/weixin/css/loading.css">
</head>

<body   onload="showHtml()">

<div id="loading" class="overlay">
    <div class="spinner" id="loading">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="page" hidden="hidden" class="userCenter">
        <?php include "left.php"; ?>
        <div class="Uheader header mm-fixed-top">
            <a href="#menu"></a>
            <ul class="oderNav recTit">
                <li><a href="javascript:;" class="active">全部订单</a></li>
                <li><a href="javascript:;">随游订单</a></li>
                <li><a href="javascript:;">过往订单</a></li>
            </ul>
        </div>


        <?php if(count($list)==0&&count($unList)==0){?>
    <div class="center_myOder slideRec" style="display:block;margin-top: 3.2rem">
        <div class="content">
            <div class="noOrder">
                <p class="">还没有订单</p>
                <img src="/assets/other/weixin/images/nodz.png" class="img">
            </div>
        </div>
    </div>
    </div>
        <?php }else{?>
            <div class="center_myOder slideRec" style="display:block;margin-top: 3.2rem">
                <div class="content">
                <?php foreach($allList as $val){
                    $tripInfo = json_decode($val['tripJsonInfo'],true);
                    $title=mb_strlen($tripInfo['info']['title'],"utf-8")>8?mb_substr($tripInfo['info']['title'],0,8,"utf-8")."...":$tripInfo['info']['title'];
                    ?>
                    <div class="box clearfix">
                        <a href="/wechat-user-center/my-order-info?id=<?php echo $val['orderNumber'] ?>"  class="pic fl"><img src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                        <div class="details fr">
                            <p><?php echo $title;?></p>
                            <p class="data">出发日期：<span><?php echo $val['beginDate'];?></span></p>
                            <p class="money">随游总价：<span>￥<?php echo $val['totalPrice'];?></span></p>
                            <?php if($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                                <p class="btns">
                                <a href="javascript:;" class="btn btn01" onclick="pay('<?= $val['orderNumber'];?>')">支付</a>
                                    <a href="javascript:;" class="btn btn02" onclick="cancelOrder('<?= $val['orderId'];?>')">取消订单</a>
                                </p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                                <p class="btns">
                                    <a href="javascript:;" class="btn btn03" onclick="refundOrder('<?= $val['orderId'];?>')">申请退款</a>
                                </p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                                <p class="btns">
                                    <a href="/wechat-user-center/apply-refund?id=<?php echo $val['orderNumber'] ?>" class="btn btn01">申请退款</a>
                                    <a href="javascript:;" class="btn btn02" onclick="confirmOrder('<?= $val['orderId'];?>')">确认游玩</a>
                                </p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                                <p class="p1">已取消</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                                <p class="p1">退款中</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                                <p class="p1">退款成功</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                                <p class="btns">
                                    <a href="javascript:alert('暂无');" class="btn btn03">去评价</a>
                                </p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                                <p class="p1">祝您旅途愉快</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                                <p class="p1">退款审核中</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                                <p class="p1">退款审核失败</p>
                            <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                                <p class="p1">随友取消</p>
                            <?php }?>

                        </div>
                    </div>
                <?php }?>
    </div>
                </div>
    <div class="center_myOder slideRec" style="margin-top: 3.2rem">
        <div class="content">
            <?php foreach($unList as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                $title=mb_strlen($tripInfo['info']['title'],"utf-8")>8?mb_substr($tripInfo['info']['title'],0,8,"utf-8")."...":$tripInfo['info']['title'];
                ?>
                <div class="box clearfix">
                    <a href="/wechat-user-center/my-order-info?id=<?php echo $val['orderNumber'] ?>"  class="pic fl"><img src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                    <div class="details fr">
                        <p><?php echo $title;?></p>
                        <p class="data">出发日期：<span><?php echo $val['beginDate'];?></span></p>
                        <p class="money">随游总价：<span>￥<?php echo $val['totalPrice'];?></span></p>
                        <?php if($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                            <p class="btns">
                                <a href="javascript:;" class="btn btn01"  onclick="pay('<?= $val['orderNumber'];?>')">支付</a>
                                <a href="javascript:;" class="btn btn02" onclick="cancelOrder('<?= $val['orderId'];?>')">取消订单</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                            <p class="btns">
                                <a href="javascript:;" class="btn btn03" onclick="refundOrder('<?= $val['orderId'];?>')">申请退款</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                            <p class="btns">
                                <a href="/wechat-user-center/apply-refund?id=<?php echo $val['orderNumber'] ?>" class="btn btn01">申请退款</a>
                                <a href="javascript:;" class="btn btn02" onclick="confirmOrder('<?= $val['orderId'];?>')">确认游玩</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                            <p class="p1">已取消</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                            <p class="p1">退款中</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                            <p class="p1">退款成功</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                            <p class="btns">
                            <a href="javascript:alert('暂无');" class="btn btn03">去评价</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                            <p class="p1">祝您旅途愉快</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                            <p class="p1">退款审核中</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                            <p class="p1">退款审核失败</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                            <p class="p1">随友取消</p>
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <div class="center_myOder slideRec" style="margin-top: 3.2rem">
        <div class="content">
            <?php foreach($list as $val){
                $tripInfo = json_decode($val['tripJsonInfo'],true);
                $title=mb_strlen($tripInfo['info']['title'],"utf-8")>8?mb_substr($tripInfo['info']['title'],0,8,"utf-8")."...":$tripInfo['info']['title'];
                ?>
                <div class="box clearfix">
                    <a href="/wechat-user-center/my-order-info?id=<?php echo $val['orderNumber'] ?>"  class="pic fl"><img src="<?php echo $tripInfo['info']['titleImg'];?>"></a>
                    <div class="details fr">
                        <p><?php echo $title;?></p>
                        <p class="data">出发日期：<span><?php echo $val['beginDate'];?></span></p>
                        <p class="money">随游总价：<span>￥<?php echo $val['totalPrice'];?></span></p>
                        <?php if($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_WAIT){?>
                            <p class="btns">
                                <a href="javascript:;" class="btn btn01"   onclick="pay('<?= $val['orderNumber'];?>')">支付</a>
                                <a href="javascript:;" class="btn btn02" onclick="cancelOrder('<?= $val['orderId'];?>')">取消订单</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PAY_SUCCESS){?>
                            <p class="btns">
                                <a href="javascript:;" class="btn btn03" onclick="refundOrder('<?= $val['orderId'];?>')">申请退款</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CONFIRM){?>
                            <p class="btns">
                                <a href="/wechat-user-center/apply-refund?id=<?php echo $val['orderNumber'] ?>" class="btn btn01">申请退款</a>
                                <a href="javascript:;" class="btn btn02" onclick="confirmOrder('<?= $val['orderId'];?>')">确认游玩</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_CANCELED){?>
                            <p class="p1">已取消</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_WAIT){?>
                            <p class="p1">退款中</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_SUCCESS){?>
                            <p class="p1">退款成功</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_SUCCESS){?>
                            <p class="btns">
                                <a href="javascript:alert('暂无');" class="btn btn03">去评价</a>
                            </p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PLAY_FINISH){?>
                            <p class="p1">祝您旅途愉快</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_VERIFY){?>
                            <p class="p1">退款审核中</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_REFUND_FAIL){?>
                            <p class="p1">退款审核失败</p>
                        <?php }elseif($val['status']==\common\entity\UserOrderInfo::USER_ORDER_STATUS_PUBLISHER_CANCEL){?>
                            <p class="p1">随友取消</p>
                        <?php }?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <?php }?>
    <div class="order_pay clearfix">
        <p>选择支付方式</p>
        <div class="select clearfix">
            <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
            <a href="javascript:;" class="wei" onclick="payUrl()"></a>
            <a href="javascript:;" class="btn" id="qxPay">取消</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#qxPay').click(function(e) {
            $('.order_pay').animate({height:'0'},500);
        });

    })
    var urlR="";
    var urlA="";
    function pay(orderNumber)
    {
        $('.order_pay').animate({height:'6.5rem'},500);
        urlR ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/wxpay-js?t=2&n="+orderNumber;
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/ali-pay-url?t=2&o="+orderNumber;

    }
    function payUrl()
    {
        if(urlR=="")
        {
            alert("未知的订单");
            return;
        }
        window.location.href=urlR;
    }
    function aliPayUrl()
    {
        if(urlA=="")
        {
            alert("未知的订单");
            return;
        }
        window.location.href=urlA;
    }
</script>
<script>

    function refundOrder(orderId)
    {
        $.ajax({
            url :'/wechat-user-center/refund-order',
            type:'post',
            data:{
                orderId:orderId
            },
            error:function(){
                //hide load
                alert('退款失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert('退款失败');
                }
            }
        });
    }
    //取消
    function cancelOrder(id)
    {
        var r=confirm("是否确认取消订单")
        if (r==true)
        {

        }
        else
        {
           return;
        }
        $.ajax({
            url :'/wechat-user-center/cancel-order',
            type:'post',
            data:{
                orderId:id
            },
            error:function(){
                //hide load
                alert('取消失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert(data.data);
                }
            }
        });
    }
    //确认
    function confirmOrder(id){
        $.ajax({
            url :'/wechat-user-center/user-confirm-play',
            type:'post',
            data:{
                orderId:id
            },
            error:function(){
                //hide load
                alert('确认失败');
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-center/my-order";
                }else{
                    alert('确认失败');
                }
            }
        });
    }
</script>
</body>
</html>
