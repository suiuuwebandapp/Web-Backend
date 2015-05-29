<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">修改定制订单</span>
                    <span class="caption-helper"> 修改定制订单 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false" onsubmit="return false">
                    <input type="hidden" id="orderNumber" value="<?=$info['wOrderNumber']?>" class="form-control" />
                    <input type="hidden" id="status" value="<?=$info['wStatus']?>" class="form-control" />
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
                            <label class="col-md-3 control-label">目标城市</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['wOrderSite']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">日期列表</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['wOrderTimeList']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">出游人数</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['wUserNumber']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">出行需求</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['wOrderContent']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">用户手机</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['wPhone']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">订单状态</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <label class="col-md-3 control-label" style="text-align: left">
                                        <?php
                                        switch($info['wStatus'])
                                        {
                                            case 1:
                                                echo "请处理";
                                            break;
                                            case 2:
                                                echo "未付款";
                                                break;
                                            case 3:
                                                echo "已付款";
                                                break;
                                            case 4:
                                                echo "游玩结束";
                                                break;
                                            case 5:
                                                echo "申请退款";
                                                break;
                                            case 6:
                                                echo "退款结束";
                                                break;

                                        }
                                        ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">负责人昵称</label>
                            <div class="col-md-9 control-label" style="text-align: left">
                                <?=$info['rNickName']?>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">负责人手机</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="rPhone" value="<?=$info['rPhone']?>" class="form-control" placeholder="请输入负责人手机" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">订单金额</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="money" value="<?=$info['wMoney']?>" class="form-control" placeholder="请输入金额" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">详细计划</label>
                            <div class="col-md-9 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea id="wDetails" class="form-control"  placeholder="详细计划" required><?=$info['wDetails']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;更新定制&nbsp;&nbsp;</button>
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
        FormValidation.init("editArticle");
    });
    //添加专栏文章
    function editArticle(){
        var orderNumber=$("#orderNumber").val();
        var rPhone=$("#rPhone").val();
        var money=$("#money").val();
        var wDetails=$("#wDetails").val();
        var status=$("#status").val();
        if(orderNumber==''){
            Main.errorTip("订单号异常，请刷新后重试");
            return;
        }
        if(rPhone==''){
            Main.errorTip("负责人不能为空");
            return;
        }
        if(money==''){
            Main.errorTip("订单金额不能为空");
            return;
        }
        if(wDetails==''){
            Main.errorTip("定制详细信息不能为空");
            return;
        }
        if(status>2){
            Main.errorTip("订单已经处理，无法修改");
            return;
        }
        $.ajax({
            url :'/wechat-order/edit-order',
            type:'post',
            cache:false,
            data:{
                orderNumber:orderNumber,
                rPhone:rPhone,
                money:money,
                wDetails:wDetails
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
                    Main.goAction("/wechat-order/list");
                }else{
                    Main.errorTip("更新失败,错误信息:"+datas.data);
                }
            }
        });
    }

</script>


