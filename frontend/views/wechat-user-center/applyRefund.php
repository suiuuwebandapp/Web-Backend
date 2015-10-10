
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">申请成功</p>
    </div>
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
                _csrf: $('input[name="_csrf"]').val(),
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
