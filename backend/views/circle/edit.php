<?php

?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/select2/select2_metro.css">
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/uploadifive.css">

    <style type="text/css">
        .uploadifive-button{
            background: #1bbc9b;
            color: #ffffff;
            border: none;
            width: 120px !important;
            height: 35px !important;
            line-height: 36px !important;
            font-family: "Microsoft YaHei";
            font-size: 14px;
            font-weight: normal;
        }
        .uploadifive-queue-item{
            margin-bottom: 10px;
        }
        #titleImgPre{
            margin-bottom: 10px;
            max-height: 150px;
        }
    </style>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    编辑推荐
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 300px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <input id="id" value="<?= $info['recommendId'];?>" type="hidden"/>
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
                                        <label class="col-md-3 control-label">推荐类型<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios"  value="1" <?php if($info['relativeType']==1){echo "checked";}?> >
                                                    用户
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios" value="2" <?php if($info['relativeType']==2){echo "checked";}?> >
                                                    帖子
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios"  value="3" <?php if($info['relativeType']==3){echo "checked";}?> >
                                                    随游
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios"  value="4" <?php if($info['relativeType']==4){echo "checked";}?> >
                                                    圈子
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">推荐编号<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input id="rId" class="form-control" placeholder="请输入推荐编号" value="<?php echo $info['relativeId']?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <input type="hidden" id="titleImg" value="<?php echo $info['rImg'];?>"/>
                                            <img id="titleImgPre" src="<?php echo $info['rImg'];?>"/>
                                            <div id="queue"></div>
                                            <input id="file_upload" name="file_upload" type="file">
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


<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        FormValidation.init("add");
        Metronic.initSlimScroll($(".scroller"));
    });


    <?php $timestamp = time();?>
    $(function() {
        $('#file_upload').uploadifive({
            'auto'             : true,
            'buttonText'       : '请选择封面图',
            'queueID'          : 'queue',
            'uploadScript'     : '/upload/upload-title-img',
            'multi'            : false,
            'onUploadComplete' : function(file, data) {
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("上传封面图成功");
                    $("#queue").html("");
                    $("#titleImg").val(datas.data);
                    $("#titleImgPre").attr("src",datas.data);
                    $("#titleImgPre").show();
                }else{
                    Main.errorTip("上传封面图失败");
                }
            }
        });
    });


    //添加目的地
    function add(){
        var id=$("#id").val();
        var rId=$("#rId").val();
        var img=$("#titleImg").val();
        var type=$("input[name='optionsRadios']:checked").val();
        if(id==''){
            Main.errorTip("编号不允许为空");
            return;
        }
        if(rId==''){
            Main.errorTip("推荐编号不允许为空");
            return;
        }
        if(type==''){
            Main.errorTip("推荐类型不允许为空");
            return;
        }
        if(type=="1")
        {
            if(img=="")
            {
                Main.errorTip("推荐用户需要添加背景图片");
                return;
            }
        }
        $.ajax({
            url :'/recommend-list/edit',
            type:'post',
            data:{
                id:id,
                rId:rId,
                img:img,
                type:type
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("添加推荐成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("添加目的地失败,错误信息:<br/>"+datas.data);
                }
            }
        });
    }


</script>
</body>
</html>
