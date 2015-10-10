
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">我的定制</p>
    </div>
<div class="con cdzOder clearfix">
    <div class="content">
    <a href="/we-chat-order-list/order-view" class="bgBlue colWit dbtnfixed">添加定制</a>
    <?php
        $i=0;
        foreach($list as $val){
            $i++;
        if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_NORMAL){
        ?>

            <div class="box clearfix"">
                <div class="pic fl">
                    <a href="" class="userPic"><img src="/assets/other/weixin/images/logo02.png"></a>
                    <p class="name">未分配</p>
                </div>
                <div class="details fr" id="details">
                    <p class="data">目的地：<span><?php echo $val['wOrderSite'];?></span></p>
                    <p class="data">日期：
                    <?php
                    $dateList=$val['wOrderTimeList'];
                    $dataArr=explode(',',$dateList);
                        ?>
                        <span><?php echo $dataArr[0];if(count($dataArr)>2){echo " ...";}?></span>
                    </p>
                    <p class="data">联系方式：<span><?php echo $val['wPhone'];?></span></p>
                    <p class="btns">
                        <a href="/we-chat-order-list/edit-order?orderNumber=<?=$val['wOrderNumber'];?>" class="btn btn01" id="xg">编辑修改</a>
                        <a href="javascript:;" class="btn btn02" id="qx" onclick="cancelOrder('<?php echo $val['wOrderNumber']?>')">取消定制</a>
                    </p>
                </div>
            </div>
     <?php }else{ ?>
    <div class="box clearfix"">
    <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END
        ||$val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS
        ||$val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL
        ||$val['wStatus']==\common\entity\WeChatOrderList::STATUS_CANCEL
    ){?>
    <a href="javascript:;" class="close" onclick="deleteOrder('<?php echo $val['wOrderNumber']?>')"></a>
    <?php }?>
        <div class="pic fl">
            <?php if(empty($val['wRelativeSign'])){?>
                <a href="javascript:;" class="userPic"><img src="/assets/other/weixin/images/logo02.png"></a>
            <?php }else{ ?>
                <a href="/wechat-user-info/trip-list?userSign=<?php echo $val['wRelativeSign']?>" class="userPic"><img src="<?php echo $val['headImg']?>"></a>
            <?php }?>
            <p class="name"><?php echo $val['nickName'];?></p>
            <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
                <p class="state01">待支付</p>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                <p class="state02">游玩中</p>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
                <p class="state03">游玩结束</p>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                <p class="state01">退款中</p>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
                <p class="state03">结束退款</p>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
                <p class="state03">拒绝退款</p>
            <?php }else{?>
                <p class="state03">已经取消</p>
            <?php }?>
        </div>
        <div class="details fr" id="details">
            <p class="data">目的地：<span><?php echo $val['wOrderSite'];?></span></p>
            <p class="data">日期：
                <?php
                $dateList=$val['wOrderTimeList'];
                $dataArr=explode(',',$dateList);
                ?>
                <span><?php echo $dataArr[0];if(count($dataArr)>2){echo " ...";}?></span>
            </p>
            <p class="data">负责人：<span class="colGreen"><a class="colGreen" href="tel:<?php echo $val['phone'];?>"><?php echo $val['phone'];?></a></span></p>
            <p class="money">总价：<span>￥<?php echo $val['wMoney']?$val['wMoney']:0 ?></span></p>
            <?php if($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PROCESSED){?>
                <p class="btns">
                    <a id="payC" href="javascript:;" class="btn btn01" onclick="callpay('<?php echo $val['wOrderNumber']?>')">立即支付</a>
                    <a href="javascript:;" class="btn btn02" onclick="cancelOrder('<?php echo $val['wOrderNumber']?>')">取消定制</a>
                </p>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_PAY_SUCCESS){?>
                <p class="btns">
                    <a href="/we-chat-order-list/show-refund?o=<?=$val['wOrderNumber'];?>" class="btn btn01">申请退款</a>
                    <a href="javascript:;" class="btn btn02" onclick="overOrder('<?php echo $val['wOrderNumber']?>')">确认游玩</a>
                </p>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_END){?>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_APPLY_REFUND){?>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_SUCCESS){?>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }elseif($val['wStatus']==\common\entity\WeChatOrderList::STATUS_REFUND_FAL){?>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }else{?>
                <a href="/we-chat-order-list/order-info?orderNumber=<?php echo $val['wOrderNumber'];?>" class="seen">查看行程</a>
            <?php }?>


        </div>
    </div>

        <?php }?>
    <?php }?>
    </div>
</div>
<div class="order_pay clearfix" style="z-index: 9999">
    <p>选择支付方式</p>
    <div class="select clearfix">
        <a href="javascript:;" class="zfb" onclick="aliPayUrl()"></a>
        <a href="javascript:;" class="wei" onclick="payUrl()"></a>
        <a href="javascript:;" class="btn" id="qxPay">取消</a>
    </div>
</div>


<script>
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


   /* $(document).ready(function(){
        $(".box.clearfix").bind('click', clickKb);
    });
    function clickKb(e){
        alert($(e.target)[0].class );return;
        if($(e.target)[0].id !="xg"&&$(e.target)[0].id !="qx"){
            window.location.href="/we-chat-order-list/order-info?orderNumber="+$(e.target)[0].id ;
        }
            return;
    }*/

    function cancelOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/cancel-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
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
    function overOrder(orderNumber)
    {
        $.ajax({
            url :'/we-chat-order-list/over-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                o:orderNumber
            },
            error:function(){
                alert("结束订购异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    alert("确认成功");
                    setTimeout(function(){location.reload()},1000);
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert("确认异常");
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
                _csrf: $('input[name="_csrf"]').val(),
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

