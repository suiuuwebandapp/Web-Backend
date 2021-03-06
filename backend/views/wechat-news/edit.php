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
                    <span class="caption-subject font-red-sunglo bold uppercase">微信消息</span>
                    <span class="caption-helper"> 编辑消息 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false">
                    <input type="hidden" id="newsId" value="<?=$info['newsId'] ?>" class="form-control" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tid</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="Tid" value="<?=$info['nTid']?>" class="form-control" placeholder="请输入相对类型id"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">标题</label>
                                <div class="col-md-4 valdate">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <input type="text" id="title" value="<?=$info['nTitle']?>" class="form-control" placeholder="请输入图文消息标题"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">简介</label>
                                <div class="col-md-4 valdate">
                                    <div class="input-icon right">
                                        <i class="fa"></i>
                                        <input type="text" id="nIntro" value="<?=$info['nIntro']?>" class="form-control" placeholder="请输入图文消息简介"  />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">类型<span class="required">*</span></label>
                                <div class="col-md-4" style="padding-left: 30px">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <input type="radio" name="optionsRadios"  value="1" <?php if($info['nType']==1){echo "checked";}?> onclick="changedx(1)" >
                                            文本
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optionsRadios" value="2"  <?php if($info['nType']==2){echo "checked";}?> onclick="changedx(2)" >
                                            图文
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optionsRadios" value="3"  <?php if($info['nType']==3){echo "checked";}?> onclick="changedx(3)" >
                                            图片
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optionsRadios" value="4"  <?php if($info['nType']==4){echo "checked";}?> onclick="changedx(1)" >
                                            学校
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">关键字</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="nAntistop" value="<?=$info['nAntistop']?>" class="form-control" placeholder="请输入回复关键字"  <?php if(strstr($info['nAntistop'],"禁止修改")){echo "disabled";}?>/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">URL</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="nUrl" value="<?=$info['nUrl']?>" class="form-control" placeholder="请输入图文消息指向URL"  />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图</label>
                            <div class="col-md-4 valdate">
                                <input type="hidden" id="titleImg" value="<?=$info['nCover']?>" />
                                <img id="titleImgPre" src="<?=$info['nCover']?>"/>
                                <div id="queue"></div>
                                <input id="file_upload" name="file_upload" type="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">内容</label>
                            <div id="txt_id1"  class="col-md-6" <?php if($info['nType']==2){echo 'style="display: none"';}?> >
                                <textarea id="textareaid1" class="col-md-6"><?=$info['nContent']?></textarea>
                            </div>
                            <div id="txt_id2"  class="col-md-6" <?php if($info['nType']!=2){echo 'style="display: none"';}?>>

                                <script id="container" name="content" type="text/plain" style="height:300px;"><?=$info['nContent']?></script>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;编辑消息&nbsp;&nbsp;</button>
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
        //$("#titleImgPre").hide();//隐藏预览封面图


        $('#file_upload').uploadifive({
            'auto'             : true,
            'buttonText'       : '请选择封面图',
            'queueID'          : 'queue',
            'uploadScript'     : '/upload/upload-wechat-img',
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


    function delHtmlTag(str){
        return str.replace(/<[^>]+>/g,"");//去掉所有的html标记
    }
    function changedx(i)
    {
        if(i==1||i==4)
        {
            $('#txt_id1').show();
            $('#txt_id2').hide();
        }else if(i==2)
        {
            $('#txt_id1').hide();
            $('#txt_id2').show();
        }
        else if(i==3)
        {
            alert("图片消息请删除后重新添加");
            $('#txt_id1').show();
            $('#txt_id2').hide();
        }
    }

    function addNews(){

        var newsId=$("#newsId").val();
        var Tid=$("#Tid").val();
        var title=$("#title").val();
        var nIntro=$("#nIntro").val();
        var nAntistop=$("#nAntistop").val();
        var nUrl=$("#nUrl").val();
        var type=$('input:radio:checked').val();
        var titleImg=$("#titleImg").val();
        var content=ue.getContent();
        if(newsId=="")
        {
            Main.errorTip("未知的消息编号");
            return;
        }
        if(type==""||type==undefined)
        {
            Main.errorTip("消息类型不允许为空");
            return;
        }
        if(type=="1"||type=="4"){
            content =  $('#textareaid1').val();
            if(content==''){
                Main.errorTip("文本消息不允许为空");
                return;
            }
        }else{
            if(titleImg==''){
                Main.errorTip("封面图不允许为空");
                return;
            }
            if(title==''){
                Main.errorTip("标题不允许为空");
                return;
            }
        }
        $.ajax({
            url :'/wechat-news/edit',
            type:'post',
            cache:false,
            data:{
                newsId:newsId,
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
                Main.errorTip("编辑失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("编辑成功");
                    Main.goAction("/wechat-news/list");
                }else{
                    Main.errorTip("编辑失败,错误信息:"+datas.data);
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


