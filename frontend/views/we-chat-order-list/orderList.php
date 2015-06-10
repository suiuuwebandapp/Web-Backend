<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>随游-订单</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>
</head>

<body>
<a href="/we-chat-order-list/order-view" class="fixedBtn">添加订单</a>
<div class="con sy_order clearfix">
    <?php foreach($list as $val){
        if(empty($val['wRelativeSign'])||$val['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL){
        ?>
    <div class="box clearfix">
        <div class="top clearfix">
            <a href="javascript:;" class="delete" onclick="deleteOrder('<?php echo $val['wOrderNumber']?>')"></a>
            <div class="left">
                <a href="#" class="user">
                    <img src="/assets/other/weixin/images/logo01.png" class="logo">
                </a>
                <span class="name">未分配</span>
            </div>
            <div class="right">
                <p>城市：<b><?php echo $val['wOrderSite'];?></b></p>
                <?php
                $dateList=$val['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                foreach($dataArr as $dataV){
                ?>
                <p>日期：<b><?php echo $dataV;?></b></p>
                <?php } ?>
            </div>
        </div>
        <div class="down clearfix">
            <p><?php echo $val['wOrderContent'];?></p>
        </div>
        <b class="money">待定&nbsp</b>
        <a href="#" class="btn paying">处理中</a>
    </div>
     <?php }else{ ?>
            <div class="box clearfix">
                <div class="top clearfix">
                    <?php if($val['wStatus']!=\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS&&$val['wStatus']!=\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                    <a href="javascript:;" class="delete" onclick="deleteOrder('<?php echo $val['wOrderNumber']?>')"></a>
                    <?php }?>
                    <div class="left"><a href="#" class="user"><img src="<?php echo $val['headImg']?>" class="logo"></a><span class="name"><?php echo $val['nickName'];?></span></div>
                    <div class="right">
                        <p>城市：<b><?php echo $val['wOrderSite'];?></b></p>
                        <?php
                        $dateList=$val['wOrderTimeList'];
                        $dataArr=explode(',',$dateList);
                        foreach($dataArr as $dataV){
                            ?>
                            <p>日期：<b><?php echo $dataV;?></b></p>
                        <?php } ?>
                        <p>手机：<a href="tel:<?php echo $val['areaCode'].$val['phone'];?>"><?php echo $val['areaCode'].$val['phone'];?></a></p>
                    </div>
                </div>
                <div class="down clearfix">
                    <p><?php echo $val['wDetails'];?></p>
                </div>
                <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                    <b class="money money2">￥ <?php echo $val['wMoney'];?></b>
                <?php }else{?>
                    <b class="money ">￥ <?php echo $val['wMoney'];?></b>
                <?php }?>
                <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                    <a href="/we-chat-order-list/show-refund?o=<?php echo $val['wOrderNumber']?>" class="btn payback">申请退款</a>
                    <a href="javascript:;" class="btn sure" onclick="overOrder('<?php echo $val['wOrderNumber']?>')">确认游玩</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
                    <?php if($val['wMoney']!=0){?>
                    <a href="javascript:;" class="btn pay" onclick="callpay('<?php echo $val['wOrderNumber']?>')">支付</a>
                    <?php }else{?>
                        <a href="javascript:alert('金额不能为0');" class="btn finish" >支付</a>
                    <?php }?>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                    <a href="javascript:;" class="btn finish">退款中</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
                    <a href="javascript:;" class="btn finish">已结束</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
                    <a href="javascript:;" class="btn finish">拒绝退款</a>
                <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
                    <a href="javascript:;" class="btn finish">退款成功</a>
                <?php }else{?>
                    <a href="javascript:;" class="btn finish">已结束</a>
                <?php }?>
            </div>
        <?php }?>
    <?php }?>
    </div>
</div>
<div class="order_pay clearfix">
    <p>选择支付方式</p>
    <div class="select clearfix">
        <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
        <a href="javascript:;" class="wei" onclick="payUrl()"></a>
        <a href="javascript:;" class="btn">取消</a>
    </div>
</div>

<script>
    function overOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/over-order',
            type:'post',
            data:{
                o:orderNumber
            },
            error:function(){
                alert("结束订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert(data.data);
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
    function deleteOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/delete-order',
            type:'post',
            data:{
                orderNumber:orderNumber
            },
            error:function(){
                alert("删除订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert(data.data);
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    var urlR="";
    var urlA="";
    function callpay(orderNumber)
    {
        urlR ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/wxpay-js?n="+orderNumber;
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat-order-list/ali-pay-url?o="+orderNumber;

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
</body>
</html>
