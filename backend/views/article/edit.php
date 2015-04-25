<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午4:57
 * Email: zhangxinmailvip@foxmail.com
 */

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
                    <span class="caption-subject font-red-sunglo bold uppercase">修改专栏文章</span>
                    <span class="caption-helper"> 修改专栏文章基本信息 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false">
                    <input type="hidden" id="articleId" value="<?=$articleInfo->articleId ?>" class="form-control" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">标题<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="title" value="<?=$articleInfo->title ?>" class="form-control" placeholder="请输入文章标题" maxlength="20"  required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">名称<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="name" value="<?=$articleInfo->name ?>" class="form-control" placeholder="请输入文章名称" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <input type="hidden" id="titleImg" value="<?=$articleInfo->titleImg ?>"/>
                                <img id="titleImgPre" src="<?=$articleInfo->titleImg ?>" />
                                <div id="queue"></div>
                                <input id="file_upload" name="file_upload" type="file">
                            </div>
                        </div>

                        <!--
                        <div class="form-group">
                            <label class="col-md-3 control-label">国家&nbsp;</label>
                            <div class="col-md-4">
                                <select id="kindIds" name="kindIds" class="form-control muti_select" placeholder=" 请选电影分类"  required>
                                        <option value=""></option>
                                        <option value="0">中国</option>
                                        <option value="1">日本</option>
                                        <option value="2">韩国</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">城市&nbsp;</label>
                            <div class="col-md-4">
                                <select id="kindIds" name="tagIds" class="form-control muti_select" placeholder=" 请选电影分类"  required>
                                        <option value=""></option>
                                        <option value="0">北京</option>
                                        <option value="1">上海</option>
                                        <option value="2">郑州</option>
                                </select>
                            </div>
                        </div>
                        -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">文章内容<span class="required">*</span></label>
                            <div class="col-md-6">
                                <script id="container" name="content" type="text/plain" style="height:300px;"><?=$articleInfo->content ?> </script>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;保存专栏&nbsp;&nbsp;</button>
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
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/form-validation.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>



<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.config.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.all.min.js?<?=time().rand(100,999)?>"></script>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>


<script type="text/javascript">


    $(document).ready(function() {
        FormValidation.init("editArticle");
    });


    //初始化ueditor
    var ue = UE.getEditor('container');
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


    //添加专栏文章
    function editArticle(){
        var articleId=$("#articleId").val();
        var title=$("#title").val();
        var name=$("#name").val();
        var titleImg=$("#titleImg").val();
        var content=ue.getContent();

        if(articleId==''){
            Main.errorTip("获取专栏编号异常，请刷新后重试");
            return;
        }
        if(titleImg==''){
            Main.errorTip("封面图不允许为空");
            return;
        }
        if(content==''){
            Main.errorTip("文章内容不允许为空");
            return;
        }

        $.ajax({
            url :'/article/edit-article',
            type:'post',
            data:{
                articleId:articleId,
                title:title,
                name:name,
                titleImg:titleImg,
                content:content,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("保存专栏失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("保存专栏成功");
                    Main.goAction("/article/list");
                }else{
                    Main.errorTip("保存专栏失败,错误信息:"+datas.data);
                }
            }
        });
    }

</script>


