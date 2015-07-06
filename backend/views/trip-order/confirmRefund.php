<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/17
 * Time : 上午9:40
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<?php
if($refundApplyInfo!=null){
    $applyInfo=$refundApplyInfo['info'];
    $orderInfo=$refundApplyInfo['orderInfo'];
}else{
    throw new \yii\base\Exception("无效的退款申请信息");
}
?>

<!DOCTYPE html>
<head>
</head>
<body>
<input type="hidden" id="refundApplyId" value="<?=$applyInfo['refundApplyId']?>" />
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 550px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    确认退款信息
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 400px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">订单号&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" placeholder="订单号" value="<?=$orderInfo['orderNumber']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">订单金额&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" placeholder="订单号" value="<?=$orderInfo['totalPrice']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">退款账户信息<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="accountInfo" name="accountInfo" placeholder="退款到达的账户信息" class="form-control" required maxlength=50 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">退款交易号<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="refundNumber" name="refundNumber" placeholder="请填写支付宝或者微信的退款交易号" class="form-control" required maxlength=100 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">退款金额<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="money" name="money" placeholder="实际退款金额" class="form-control" required maxlength=15 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">备注&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="content" placeholder="请填写备注信息" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn green-meadow">
                    确认打款
                </button>
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/form-validation.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        FormValidation.init("confirmOrderRefund");
    });

    var confirmOrderRefund=function (){
        var refundApplyId=$("#refundApplyId").val();
        var money=$("#money").val();
        var content=$("#content").val();
        var accountInfo=$("#accountInfo").val();
        var refundNumber=$("#refundNumber").val();

        if(isNaN(money)){
            FormValidation.setError($("#money"),"请填写正确的金额");
            return;
        }

        $.ajax({
            type:"POST",
            url:"/trip-order/confirm-refund",
            data:{
                refundApplyId:refundApplyId,
                money:money,
                accountInfo:accountInfo,
                content:content,
                refundNumber:refundNumber
            },beforeSend:function(){
                Main.showWait(".modal-dialog");
            },
            error:function(){
                Main.hideWait();
                Main.errorTip("系统异常");
            },
            success:function(data){
                data= $.parseJSON(data);
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("确认退款成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("确认退款失败");
                }
            }
        });

    }

</script>
</body>
</html>