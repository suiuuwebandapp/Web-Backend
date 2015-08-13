<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/20
 * Time : 下午1:24
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
                    设置随游评分（随游名称：<?=$tripInfo['title']?>）
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
                                        <label class="col-md-3 control-label">随游评分<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="score" value="<?=$tripInfo['score']?>" class="form-control" placeholder="请输入随游评分" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">随游次数<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="tripCount" value="<?=$tripInfo['tripCount']?>" class="form-control" placeholder="请输入随游评分" required/>
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
        FormValidation.init("updateRecommend");
    });



    //添加目的地
    function updateRecommend(){
        var score=$("#score").val();
        var tripCount=$("#tripCount").val();
        var tripId=$("#tripId").val();
        if(isNaN(score)){
            alert("请输入正确的随游评分");
            return;
        }
        if(isNaN(tripCount)){
            alert("请输入正确的随游次数");
            return;
        }
        $.ajax({
            url :'/trip/update-trip-info',
            type:'post',
            data:{
                tripId:tripId,
                tripCount:tripCount,
                score:score
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("更改随游信息失败");
                Main.hideWait();
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("更改随游信息成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("更改随游信息失败:<br/>"+datas.data);
                }
            }
        });
    }



</script>
</body>
</html>
