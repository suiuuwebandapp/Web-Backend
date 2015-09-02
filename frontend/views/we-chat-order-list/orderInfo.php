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
    <script type="text/javascript" src="/assets/other/weixin/js/weixin.js"></script>
    <link rel="stylesheet" href="/assets/other/weixin/css/jquery.mmenu.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery.mmenu.min.js"></script>
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

<body  class="bgwhite" onload="showHtml()">

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
        <p class="navTop">定制详情</p>
    </div>
    <?php if($info['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL||empty($info['wRelativeSign'])){?>
        <div class="con cdzOder clearfix">
            <div class="content cdzdetail02">
                <p>目的地：<span><?= $info['wOrderSite'];?></span></p>
                <p>出行人数：<span><?= $info['wUserNumber'];?>人</span></p>
                <?php
                $dateList=$info['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                foreach($dataArr as $dataV){
                    ?>
                    <p>随游陪同日期：<span><?php echo $dataV;?></span></p>
                <?php } ?>
                <p>旅行需求：<span><?php
                        $arr = explode("||",$info['wOrderContent']);
                        if(count($arr)==4)
                        {
                            $lx=explode(":",$arr[1])[1];
                            $dy=explode(":",$arr[2])[1];
                            $str = explode(":",$arr[0])[1]."、".join("、",explode(",",$lx))."、"."导游：".join("、",explode(",",$dy));
                            echo $str;
                        }
                        ?>
                    </span></p>
                <p>其他留言：<span><?php $arr = explode("||",$info['wOrderContent']);if(count($arr)==4)
                        {
                            echo $arr[3];
                        }else
                        {
                            echo $info['wOrderContent'];
                        }
                        ?> </span></p>
                <div class="fixed">
                    <a href="/we-chat-order-list/edit-order?orderNumber=<?php echo $info['wOrderNumber'];?>" class="btn btn01">修改</a>
                    <a href="javascript:;" class="btn btn02" onclick="overOrder('<?php echo $info['wOrderNumber']?>')">取消行程</a>

                </div>
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
                            alert("取消成功");
                            setTimeout(function(){location.reload()},1000);
                        }else if(data.status==-3){
                            window.location.href=data.data;
                        }else{
                            alert("取消异常");
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
                            setTimeout(function(){ window.location.href="/we-chat-order-list/order-manage";},1000);
                        }else if(data.status==-3){
                            window.location.href=data.data;
                        }else{
                            alert(data.data);
                        }
                    }
                });
            }
        </script>
    <?php }else{?>

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
            <a href="javascript:;" class="btn finish">处理中</a>
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
</div>

<script type="text/javascript">
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
                    setTimeout(function(){ window.location.href="/we-chat-order-list/order-manage";},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
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
        urlA ="<?php echo Yii::$app->params['weChatUrl'];?>/we-chat/ali-pay-url?t=1&o="+orderNumber;

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
<?php }?>
</body>
</html>
