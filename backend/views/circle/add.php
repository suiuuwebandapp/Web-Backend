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
                    添加推荐
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 300px" data-always-visible="1" data-rail-visible1="1">
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
                                        <label class="col-md-3 control-label">圈子类型<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios"  value="1">
                                                    主题
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios" value="2">
                                                    地区
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">名称<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input id="name" class="form-control" placeholder="请输入圈子名称" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">背景图<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <input type="hidden" id="titleImg"/>
                                            <img id="titleImgPre"/>
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
        $("#titleImgPre").hide();//隐藏预览封面图
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


    function add(){
        var name=$("#name").val();
        var img=$("#titleImg").val();
        var type=$("input[name='optionsRadios']:checked").val();
        if(name==''){
            Main.errorTip("名称不允许为空");
            return;
        }
        if(type==''){
            Main.errorTip("推荐类型不允许为空");
            return;
        }
        if(img=="")
        {
            Main.errorTip("推荐用户需要添加背景图片");
            return;
        }
        $.ajax({
            url :'/circle/add',
            type:'post',
            data:{
                name:name,
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
                    Main.successTip("添加圈子成功");
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
