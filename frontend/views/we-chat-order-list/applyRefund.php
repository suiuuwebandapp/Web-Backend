<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>申请退款</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
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

<body  onload="showHtml()">

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
        <p class="navTop">申请退款</p>
    </div>
<div class="con Remoney clearfix">
    <img src="/assets/other/weixin/images/logo02.png" class="logo">
    <p>请提交审核资料,稍后我们会与您联系</p>
    <label>订单手机号</label>
    <input id="tk_phone" type="">
    <label>退款理由</label>
    <textarea id="tk_reason"></textarea>
    <a href="javascript:;" class="btn" onclick="applyRefund()">提交申请</a>

</div>
</div>
<script>
    var isClick=false;
    function applyRefund()
    {
        var orderNumber='<?php echo $orderNumber?>';
        var refundReason=$('#tk_reason').val();
        var phone=$('#tk_phone').val();
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        if(orderNumber=="")
        {
            alert('订单号不能为空');
            return;
        }
        if(refundReason=="")
        {
            alert('申请原因不能为空');
            return;
        }
        if(isClick)
        {
            alert("申请中...");
            return;
        }
        isClick=true;
        $.ajax({
            url :'/we-chat-order-list/apply-refund',
            type:'post',
            data:{
                orderNumber:orderNumber,
                refundReason:refundReason,
                phone:phone
            },
            error:function(){
                isClick=false
                alert("申请退款异常");
            },
            success:function(data){
                isClick=false
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href= '<?php echo Yii::$app->params['weChatUrl'];?>'+'/we-chat-order-list/refund-success';
                }else if(data.status==-3){
                    window.location.href=data.data;
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>
</body>
</html>
