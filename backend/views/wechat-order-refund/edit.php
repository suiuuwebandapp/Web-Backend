<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">修改退款订单</span>
                    <span class="caption-helper"> 修改退款 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false" onsubmit="return false">
                    <input type="hidden" id="orderNumber" value="<?=$info['orderNumber']?>" class="form-control" />
                    <input type="hidden" id="status" value="<?=$info['status']?>" class="form-control" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">用户昵称</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['nickName']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">订单号</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['orderNumber']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">退款理由</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['refundReason']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">负责人昵称</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['rName']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">退款金额</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="money" value="<?=$info['money']?>" class="form-control" placeholder="请输入金额" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">操作理由</label>
                            <div class="col-md-9 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea id="updateReason" class="form-control"  placeholder="操作理由" required><?=$info['updateReason']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">订单状态</label>
                            <div class="col-md-9">
                                <div class="radio-list">
                                    <label class="radio-inline">
                                        <input type="radio" name="optionsRadios"  value="1" <?php if($info['status']==1){echo "checked";}?>>
                                        未处理
                                    </label>
                                    <label class="radio-inline">
                                            <input type="radio" name="optionsRadios" value="2" <?php if($info['status']==2){echo "checked";}?>>
                                        拒绝退款
                                    </label>
                                    <label class="radio-inline">
                                            <input type="radio" name="optionsRadios"  value="3" <?php if($info['status']==3){echo "checked";}?>>
                                        确认退款
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;更新&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js""></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/form-validation.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>



<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>


<script type="text/javascript">

    $(document).ready(function() {
        FormValidation.init("editRefund");
    });
    //
    function editRefund(){
        var orderNumber=$("#orderNumber").val();
        var money=$("#money").val();
        var updateReason=$("#updateReason").val();
        var status=$('input:radio:checked').val();
        if(orderNumber==''){
            Main.errorTip("订单号异常，请刷新后重试");
            return;
        }
        if(money==''){
            Main.errorTip("订单金额不能为空");
            return;
        }
        if(updateReason==''){
            Main.errorTip("定制详细信息不能为空");
            return;
        }

        $.ajax({
            url :'/wechat-order-refund/edit-order',
            type:'post',
            cache:false,
            data:{
                orderNumber:orderNumber,
                money:money,
                updateReason:updateReason,
                status:status
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("保存失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("更新成功");
                    Main.goAction("/wechat-order-refund/list");
                }else{
                    Main.errorTip("更新失败,错误信息:"+datas.data);
                }
            }
        });
    }

</script>


