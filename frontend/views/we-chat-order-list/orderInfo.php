<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>定制订单详情</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>
</head>

<body class="bgwhite">
<div class="con cdzOder_Detail clearfix">
    <div class="box clearfix">
        <div class="down clearfix">
            <?php
            $str =$info['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
            $arr_i=array();
            $arr_t=array();
            $contentTitle="";
            if(!empty($str)){
                $arr=explode('###',$str);
                $contentTitle=$arr[0];
                for($i=1;$i<count($arr);$i++)
                {
                    if($i%2==0)
                    {
                        $arr_i[]=$arr[$i];
                    }else
                    {
                        $arr_t[]=$arr[$i];
                    }
                }
            }
            ?>
            <h3 class="title"><?php echo $contentTitle;?></h3>
            <dl>
                <?php for($j=0;$j<count($arr_i);$j++){?>
                    <?php if(empty($arr_t[$j])){ ?>
                        <dt class="title02"><?php echo $arr_i[$j];?></dt>
                    <?php }else{?>
                        <dd><span class="time"><?php echo $arr_t[$j];?> </span><p class="detail"><?php echo $arr_i[$j];?></p></dd>
                    <?php }?>
                <?php }?>
            </dl>
        </div>
        <div class="last clearfix">
        <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
            <b class="colOrange fl money">￥ <?php echo $info['wMoney'];?></b>
        <?php }else{?>
            <b class="colOrange fl money">￥ <?php echo $info['wMoney'];?></b>
        <?php }?>
        <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
            <a href="/we-chat-order-list/show-refund?o=<?php echo $info['wOrderNumber']?>" class="btn bgOrange fl">申请退款</a>
            <a href="javascript:;" class="btn bgBlue fl" onclick="overOrder('<?php echo $info['wOrderNumber']?>')">确认游玩</a>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
            <?php if($info['wMoney']!=0){?>
                <a href="javascript:;" class="btn bgBlue fl" id="payC" onclick="callpay('<?php echo $info['wOrderNumber']?>')">支付</a>
            <?php }else{?>
                <a href="javascript:alert('金额不能为0');" class="btn finish" >支付</a>
            <?php }?>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
            <a href="javascript:;" class="btn finish">退款中</a>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
            <a href="javascript:;" class="btn finish">已结束</a>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
            <a href="javascript:;" class="btn finish">拒绝退款</a>
        <?php }elseif($info['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
            <a href="javascript:;" class="btn finish">退款成功</a>
        <?php }else{?>
            <a href="javascript:;" class="btn finish">已结束</a>
        <?php }?>
        <!--状态1-->


        </div>

        <!--状态2-->
        <!--        <div class="last clearfix">
                    <b class="colOrange fl money">800￥</b>
                    <a href="#" class="btn bgBlue fl">支付</a>
                </div>
        -->


    </div>
</div>
<div class="order_pay clearfix">
    <p>选择支付方式</p>
    <div class="select clearfix">
        <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
        <a href="javascript:;" class="wei" onclick="payUrl()"></a>
        <a href="javascript:;" class="btn" id="qxPay">取消</a>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#payC').click(function(e) {
            $('.order_pay').animate({height:'6.5rem'},500);
        });
        $('#qxPay').click(function(e) {
            $('.order_pay').animate({height:'0'},500);
        });

    })
    var urlR="";
    var urlA="";
    function callpay(orderNumber)
    {
        urlR ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/wxpay-js?t=1&n="+orderNumber;
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat-order-list/ali-pay-url?t=1&o="+orderNumber;

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
        alert("暂无");
        return;
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
