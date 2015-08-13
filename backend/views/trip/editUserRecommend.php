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
    <input type="hidden" id="tripId" value="<?=$tripId?>" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    设置推荐理由
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
                                        <label class="col-md-3 control-label">推荐人&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <select id="userId" name="userId" class="form-control muti_select" placeholder=" 请选择推荐人"  required>
                                                <?php foreach($userRecommendList as $userRecommendList){ ?>
                                                    <option value="<?= $userRecommendList['userSign'] ?>"
                                                        <?php if($recommendInfo!=null&&$userRecommendList['userSign']==$recommendInfo->userId){echo "selected";}?>
                                                        ><?= $userRecommendList['nickname']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">推荐理由</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <textarea rows="5" id="content" class="form-control" placeholder="请填写推荐理由"><?=$recommendInfo==null?'':$recommendInfo->content?></textarea>
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
        var content=$("#content").val();
        var userId=$("#userId").val();
        var tripId=$("#tripId").val();

        $.ajax({
            url :'/trip/update-user-recommend',
            type:'post',
            data:{
                tripId:tripId,
                userId:userId,
                content:content
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("设置推荐失败");
                Main.hideWait();
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("设置推荐成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("设置推荐失败:<br/>"+datas.data);
                }
            }
        });
    }



</script>
</body>
</html>
