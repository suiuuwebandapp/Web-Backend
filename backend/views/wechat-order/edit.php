<?php

$str =$info['wDetails'];//'rrrr######qweqweqwe###09###ssssss######qqqqqqqq###asd###asdasdasd';
$arr_i=array();
$arr_t=array();
$contentTitle="";
if(!empty($str)){
$arr=explode('###',$str);
$contentTitle=$arr[0];
for($i=1;$i<count($arr);$i++)
{
    if($i%2==0)
    {
        $arr_i[]=$arr[$i];
    }else
    {
        $arr_t[]=$arr[$i];
    }
}
}
?>
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
                            <label class="col-md-3 control-label">负责人</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="rPhone" value="<?=$info['rPhone']?>" class="form-control" placeholder="请输入负责人手机或邮箱" maxlength="30" required/>
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
                            <label class="col-md-3 control-label">详细标题</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="content_title" value="<?=$contentTitle?>" class="form-control" placeholder="请输入标题" maxlength="50" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">详细计划</label>
                            <div class="col-md-9 valdate">
                                    <div  id="content_info">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                时间
                                                </span>
                                                    <input type="text" value="<?php echo isset($arr_t[0])?$arr_t[0]:''; ?>" class="form-control">
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                内容
                                                </span>
                                                    <input type="text" value="<?php echo isset($arr_i[0])?$arr_i[0]:''; ?>" class="form-control">
                                                    <span class="input-group-btn">

                                                <button class="btn blue" type="button" onclick="addInput()">添加</button>

                                                </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <!-- /.col-md-6 -->
                                        </div>
                                        <?php for($j=1;$j<count($arr_i);$j++){?>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                时间
                                                </span>
                                                    <input type="text" value="<?=$arr_t[$j]?>" class="form-control">
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <!-- /.col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                内容
                                                </span>
                                                    <input type="text" value="<?=$arr_i[$j]?>" class="form-control">
                                                    <span class="input-group-btn">
                                                <button class="btn blue" type="button" onclick="deleteInput(this)">删除</button>
                                                </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>
                                            <!-- /.col-md-6 -->
                                        </div>
                                        <?php }?>
                                    </div>
                                    <!-- /.row -->
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

    function showOrder(id)
    {
        window.open("<?php echo Yii::$app->params['suiuu_url']?>"+"/we-chat-order-list/sys-show-order?password=9527suiuu&id="+id);
    }
    function addInput()
    {

        var str = '<div class="row">';
        str+='<div class="col-md-2">';
        str+='<div class="input-group">';
        str+='<span class="input-group-addon">';
        str+='时间';
        str+='</span>';
        str+='<input type="text" class="form-control">';
        str+='</div>';
        str+='</div>';
        str+='<div class="col-md-6">';
        str+='<div class="input-group">';
        str+='<span class="input-group-addon">';
        str+='内容';
        str+='</span>';
        str+='<input type="text" class="form-control">';
        str+='<span class="input-group-btn">';
        str+='<button class="btn blue" type="button" onclick="deleteInput(this)">删除</button>';
        str+='</span>';
        str+='</div>';
        str+='</div>';
        str+='</div>';
        $('#content_info').append(str);
    }

    function deleteInput(obj)
    {
        $(obj).parent().parent().parent().parent().remove();
    }
    $(document).ready(function() {
        FormValidation.init("editArticle");
    });
    //
    function editArticle(){

        var str='';
        var arr = new Array();
        var content_title =$('#content_title').val();
        arr.push(content_title);
        var i=0;
        $("#content_info").find("input[type='text']").each(function () {
            arr.push($(this).val());

        });

        str=arr.join("###");
        var orderNumber=$("#orderNumber").val();
        var rPhone=$("#rPhone").val();
        var money=$("#money").val();
        var status=$("#status").val();
        if(orderNumber==''){
            Main.errorTip("订单号异常，请刷新后重试");
            return;
        }
        if(rPhone==''){
            Main.errorTip("负责人不能为空");
            return;
        }
        /*if(money==''){
            Main.errorTip("订单金额不能为空");
            return;
        }*/

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
                wDetails:str
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


