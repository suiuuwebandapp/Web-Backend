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
</head>

<body>
<div class="con Remoney clearfix">
    <img src="/assets/other/weixin/images/logo02.png" class="logo">
    <p>请提交审核资料,稍后我们会与您联系</p>
    <label>退款理由</label>
    <textarea id="tk_reason"></textarea>
    <a href="javascript:;" class="btn" onclick="applyRefund()">提交申请</a>

</div>
<script>
    function applyRefund()
    {
        var orderNumber='<?php echo $orderId?>';
        var refundReason=$('#tk_reason').val();
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
        $.ajax({
            url :'/wechat-user-center/refund-order-by-message',
            type:'post',
            data:{
                orderId:orderNumber,
                message:refundReason
            },
            error:function(){
                alert("申请退款异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href= '<?php echo Yii::$app->params['weChatUrl'];?>'+'/wechat-user-center/my-order';
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
