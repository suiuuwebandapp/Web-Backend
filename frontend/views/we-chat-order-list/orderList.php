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

<div class="con cdzOder clearfix">
    <a href="/we-chat-order-list/order-view" class="bgBlue colWit btnfixed">添加订单</a>
    <?php foreach($list as $val){
        if(empty($val['wRelativeSign'])||$val['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL){
        ?>
    <div class="box clearfix box01">
        <div class="top clearfix">
            <span class="state colOrange">处理中</span>
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
                <a href="#" class="colBlue">详情...</a>
            </div>
        </div>
    </div>
     <?php }else{ ?>
            <div class="box clearfix box01">
                <div class="top clearfix">
                    <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                        <span class="state colOrange">游玩中</span>
                    <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
                        <span class="state colOrange">待支付</span>
                    <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                        <span class="state colOrange">退款中</span>
                    <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
                        <span class="state colBlue">已结束</span>
                    <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
                        <span class="state colBlue">拒绝退款</span>
                    <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
                        <span class="state colBlue">退款成功</span>
                    <?php }else{?>
                    <span class="state colOrange">处理中</span>
                    <?php }?>
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
                        <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="colBlue">详情...</a>
                    </div>
                </div>
                <div class="down clearfix">
                    <?php
                    $str =$val['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
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
