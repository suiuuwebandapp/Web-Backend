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
if($refundInfo!=null){
    $refund=$refundInfo['info'];
    $orderInfo=$refundInfo['orderInfo'];
}else{
    throw new \yii\base\Exception("无效的退款申请记录");
}
?>

<!DOCTYPE html>
<head>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 550px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    查看退款信息
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
                                                <input type="text" id="accountInfo" name="accountInfo" class="form-control" value="<?=$refund['accountInfo']?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">退款交易号<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="refundNumber" name="refundNumber" class="form-control" value="<?=$refund['refundNumber']?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">退款金额<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="money" name="money" class="form-control" value="<?=$refund['money']?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">备注&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="content" placeholder="请填写备注信息" rows="3" readonly><?=$refund['content']?></textarea>
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
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

</body>
</html>