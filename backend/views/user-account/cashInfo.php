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
if($userCashInfo!=null){
    $cash=$userCashInfo['info'];
    $user=$userCashInfo['user'];
}else{
    throw new \yii\base\Exception("无效的退款申请记录");
}
?>

<!DOCTYPE html>
<head>
</head>
<body>
<input type="hidden" id="cashId" value="<?=$cash['cashId']?>"/>
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
                <div class="scroller" style="height: 450px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">用户昵称&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?=$user['nickname']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">手机&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?=$user['areaCode'].$user['phone']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">提现类型&nbsp;&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text"  class="form-control" value="<?=\common\entity\UserAccount::getAccountType($cash['type'])?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">提现账户&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?=$cash['account']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">姓名校验&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?=$cash['username']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">提现金额&nbsp;&nbsp;</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" value="<?=$cash['money']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">申请时间&nbsp;&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text"  class="form-control" value="<?=$cash['createTime']?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" <?=empty($cash['finishTime'])?"style='display:none'":"";?>>
                                        <label class="control-label col-md-3">处理时间&nbsp;&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" class="form-control" value="<?=$cash['finishTime']?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">提现流水号<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="cashNumber" name="cashNumber" class="form-control" value="<?=$cash['cashNumber']?>"
                                                    <?=$cash['status']==intval(\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_SUCCESS)?"readonly":"";?> />
                                            </div>
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
                <?php if($cash['status']==intval(\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_FAIL)){ ?>
                    <button type="button" class="btn green-meadow" id="successBtn">
                        打款成功
                    </button>
                <?php } ?>
                <?php if($cash['status']==intval(\common\entity\UserCashRecord::USER_CASH_RECORD_STATUS_WAIT)){ ?>
                    <button type="button" class="btn green-meadow" id="successBtn">
                        打款成功
                    </button>
                    <button type="button" class="btn red-sunglo" id="failBtn">
                        打款失败
                    </button>
                <?php } ?>
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

<script type="text/javascript">

    $(document).ready(function(){
        $("#successBtn").bind("click",function(){
            updateCashInfo(UserCashRecordType.USER_CASH_RECORD_STATUS_SUCCESS);
        });

        $("#failBtn").bind("click",function(){
            updateCashInfo(UserCashRecordType.USER_CASH_RECORD_STATUS_FAIL);
        });
    });
    var updateCashInfo=function (status){
        var cashId=$("#cashId").val();
        var cashNumber=$("#cashNumber").val();

        if(status==UserCashRecordType.USER_CASH_RECORD_STATUS_SUCCESS&&cashNumber==''){
            alert("请填写正确的提现流水号");
            return;
        }

        $.ajax({
            type:"POST",
            url:"/user-account/update-cash-info",
            data:{
                cashId:cashId,
                cashNumber:cashNumber,
                status:status
            },
            beforeSend:function(){
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
                    Main.successTip("确认提现操作成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("确认提现操作失败");
                }
            }
        });

    }
</script>
</body>
</html>