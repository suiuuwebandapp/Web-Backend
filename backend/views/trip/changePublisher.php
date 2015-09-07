<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/6
 * Time : 16:21
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<!DOCTYPE html>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <input type="hidden" id="tripId" value="<?=$tripInfo['tripId']?>" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    修改随游所属人
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 200px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide" style="display: none;">
                                        <button class="close" data-close="alert"></button>
                                        <span></span>
                                    </div>
                                    <div class="alert alert-success display-hide" style="display: none;">
                                        <button class="close" data-close="alert"></button>
                                        <span></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">当前所属人&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <img src="<?=$publisherInfo['headImg']?>" style="width: 40px;border-radius: 50px !important;"/>
                                            <b><?=$publisherInfo['nickname']?></b>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">修改所属人ID<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="newUserId" value="" class="form-control" placeholder="请输入修改所属人的编号" required/>
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
                <button type="submit" class="btn green-meadow">
                    保存
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

    $(document).ready(function() {
        FormValidation.init("changePublisher");
    });



    //添加目的地
    function changePublisher(){
        var newUserId=$("#newUserId").val();
        var tripId=$("#tripId").val();

        $.ajax({
            url :'/trip/change-publisher',
            type:'post',
            data:{
                tripId:tripId,
                newUserId:newUserId
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("修改所属人失败");
                Main.hideWait();
            },
            success:function(data){
                data= $.parseJSON(data);
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("修改所属人成功");
                    $("#modal_close").click();
                    Main.refrenshTableCurrent();
                }else{
                    Main.errorTip("修改所属人失败");
                }
            }
        });
    }



</script>
</body>
</html>