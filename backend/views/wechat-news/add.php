<?php
?>

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

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">添加专栏文章</span>
                    <span class="caption-helper"> 添加专栏文章基本信息 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tid<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="Tid" value="" class="form-control" placeholder="请输入相对类型id" maxlength="20" />
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">标题<span class="required">*</span></label>
                                <div class="col-md-4 valdate">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <input type="text" id="title" value="" class="form-control" placeholder="请输入图文消息标题" maxlength="20"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">简介<span class="required">*</span></label>
                                    <div class="col-md-4 valdate">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" id="nIntro" value="" class="form-control" placeholder="请输入图文消息简介" maxlength="20"  />
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">类型<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios"  value="1" >
                                                    文本
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optionsRadios" value="2" >
                                                    图文
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">关键字<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="nAntistop" value="" class="form-control" placeholder="请输入回复关键字" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">URL<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="nUrl" value="" class="form-control" placeholder="请输入图文消息指向URL" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <input type="hidden" id="titleImg"/>
                                <img id="titleImgPre"/>
                                <div id="queue"></div>
                                <input id="file_upload" name="file_upload" type="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">内容<span class="required">*</span></label>
                            <div class="col-md-6">
                                <script id="container" name="content" type="text/plain" style="height:300px;">
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;添加专栏&nbsp;&nbsp;</button>
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



<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.all.min.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>


<script type="text/javascript">

    var ue="";

    (function(){
        //初始化ueditor
        ue= UE.getEditor('container');
        UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
        UE.Editor.prototype.getActionUrl = function(action) {
            if (action == 'uploadimage' || action == 'uploadscrawl' || action == 'uploadimage') {
                return '/upload/upload-content-img';
            } else if (action == 'uploadvideo') {
                return 'http://a.b.com/video.php';
            } else {
                return this._bkGetActionUrl.call(this, action);
            }
        }
    })(jQuery);

    $(document).ready(function() {
        FormValidation.init("addNews");
        $("#titleImgPre").hide();//隐藏预览封面图


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




    function addNews(){

        var Tid=$("#Tid").val();
        var title=$("#title").val();
        var nIntro=$("#nIntro").val();
        var nAntistop=$("#nAntistop").val();
        var nUrl=$("#nUrl").val();
        var type=$("#name").val();
        var titleImg=$("#titleImg").val();
        var content=ue.getContent();

        if(titleImg==''){
            Main.errorTip("封面图不允许为空");
            return;
        }
        if(content==''){
            Main.errorTip("文章内容不允许为空");
            return;
        }
        $.ajax({
            url :'/wechat-news/add',
            type:'post',
            cache:false,
            data:{
                nTid:Tid,
                nTitle:title,
                nType:type,
                nAntistop:nAntistop,
                nIntro:nIntro,
                nUrl:nUrl,
                nCover:titleImg,
                nContent:content
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("添加成功");
                    initForm();
                }else{
                    Main.errorTip("添加失败,错误信息:"+datas.data);
                }
            }
        });
    }

    //初始化表单信息
    function initForm(){
        FormValidation.resetForm();

        $("#title").val("");
        $("#name").val("");
        $("#titleImg").val("");
        $("#titleImgPre").hide();
        $("#queue").html("");
        ue.setContent("");
    }

</script>


