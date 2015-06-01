<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午6:14
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">添加系统随友</span>
                    <span class="caption-helper"> 添加系统随友基本信息</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">昵称<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="nickname" value="" class="form-control" placeholder="请输入随友昵称" maxlength="20"  required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">邮箱<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="email" value="" class="form-control" placeholder="请输入随友邮箱" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">手机<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="phone" value="" class="form-control" placeholder="请输入随友手机" maxlength="30" required/>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;添加随友&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/form-validation.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>



<script type="text/javascript">


    $(document).ready(function() {
        FormValidation.init("addPublisher");
    });



    //添加专栏文章
    function addPublisher(){

        var nickname=$("#nickname").val();
        var email=$("#email").val();
        var phone=$("#phone").val();

        if(nickname==''){
            Main.errorTip("昵称不允许为空");
            return;
        }
        if(email==''&&phone==''){
            Main.errorTip("手机邮箱不能同时为空");
            return;
        }
        $.ajax({
            url :'/user-base/add-sys-publisher',
            type:'post',
            cache:false,
            data:{
                nickname:nickname,
                email:email,
                phone:phone
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加随友失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("添加随友成功");
                    initForm();
                }else{
                    Main.errorTip("添加随友失败,错误信息:"+datas.data);
                }
            }
        });
    }

    //初始化表单信息
    function initForm(){
        FormValidation.resetForm();
    }

</script>