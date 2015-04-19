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
                    <span class="caption-subject font-red-sunglo bold uppercase">添加目的地详情</span>
                    <span class="caption-helper"> 添加目的地基本信息 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" class="form-horizontal" method="post" isSubmit="false">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">标题<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="title" value="" class="form-control" placeholder="请输入文章标题" maxlength="20"  required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                            <div class="col-md-4">
                                <input type="hidden" id="titleImg"/>
                                <img id="titleImgPre"/>
                                <div id="queue"></div>
                                <input id="file_upload" name="file_upload" type="file">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">国家&nbsp;</label>
                            <div class="col-md-4">
                                <select id="countryId" name="countryIds" class="form-control muti_select" placeholder=" 请选择国家"  required>
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
                                <select id="cityId" name="cityIds" class="form-control muti_select" placeholder=" 请选择城市"  required>
                                        <option value=""></option>
                                        <option value="0">北京</option>
                                        <option value="1">上海</option>
                                        <option value="2">郑州</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;添加目的地&nbsp;&nbsp;</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn default" id="back">&nbsp;&nbsp;返回目的地列表&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
                <div class="portlet-body flip-scroll" id="table_div">
                    <form id="datatables_form" onsubmit="return false;">
                    </form>
                    <table id="table_list" class="table table-hover">
                        <thead class="flip-content">
                        <tr>
                            <th>标题</th>
                            <th>类型</th>
                            <th>大小</th>
                            <th>来源</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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


<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>


<script type="text/javascript">


    $(document).ready(function() {
        FormValidation.init("addDes");
        $("#titleImgPre").hide();//隐藏预览封面图

        $(".muti_select").select2();
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
                if(datas.status=1){
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
    function addDes(){
        var title=$("#title").val();
        var titleImg=$("#titleImg").val();
        var countryId=$("#countryId").val();
        var cityId=$("#cityId").val();

        if(titleImg==''){
            Main.errorTip("封面图不允许为空");
            return;
        }

        $.ajax({
            url :'/destination/add-destination',
            type:'post',
            data:{
                title:title,
                titleImg:titleImg,
                countryId:countryId,
                cityId:cityId
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加目的地失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("添加目的地成功");
                    initForm();
                }else{
                    Main.errorTip("添加目的地失败,错误信息:<br/>"+datas.data);
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
    }

</script>


