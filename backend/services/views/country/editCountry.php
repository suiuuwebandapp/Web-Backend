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
<head>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <input type="hidden" value="<?=$country->id; ?>" id="id" />
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    编辑国家
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 270px" data-always-visible="1" data-rail-visible1="1">
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
                                        <label class="control-label col-md-3">中文名称<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="cname" name="cname" class="form-control" value="<?= $country->cname; ?>" required maxlength=20 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">英文名称<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="ename" name="ename" class="form-control" value="<?= $country->ename; ?>" required maxlength=20 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">代码&nbsp;&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="code" name="code" class="form-control" value="<?= $country->code; ?>" maxlength=20 />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">手机代码</label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="areaCode" name="areaCode" class="form-control" value="<?= $country->areaCode; ?>" maxlength=20 />
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

<script type="text/javascript" src="/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="/assets/admin/pages/scripts/form-validation.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        FormValidation.init("updateCountry");
    });

    /**
     * 更新国家
     */
    function updateCountry(){
        $.ajax({
            type:"POST",
            url:"/country/update-country",
            data:{
                id:$("#id").val(),
                cname:$("#cname").val(),
                ename:$("#ename").val(),
                code:$("#code").val(),
                areaCode:$("#areaCode").val()

            },beforeSend:function(){
                Main.showWait(".modal-dialog");
            },
            error:function(){
                Main.errorTip("系统异常");
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("保存国家成功");
                    $("#modal_close").click();
                    Main.refrenshTableCurrent();
                }else{
                    Main.errorTip("保存失败");
                }
            }
        });
    }

</script>
</body>
</html>
