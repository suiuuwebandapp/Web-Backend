<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/20
 * Time : 下午1:24
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/uploadifive.css">
    <link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css">

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
    <input type="hidden" id="desId" value="<?= $desId?>"/>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    添加景区信息
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 400px" data-always-visible="1" data-rail-visible1="1">
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
                                        <label class="col-md-3 control-label">景点名称<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="title" value="" class="form-control" placeholder="请输入文章标题" maxlength="20"  required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">景点简介<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <textarea id="intro" class="form-control" placeholder="请输入景区简介" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">开始时间<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="beginTime" class="form-control timepicker timepicker-no-seconds" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">结束时间<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="endTime" class="form-control timepicker timepicker-no-seconds" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <input type="hidden" id="titleImg"/>
                                            <img id="titleImgPre"/>
                                            <div id="queue"></div>
                                            <input id="file_upload" name="file_upload" type="file">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left: 54px;">
                                    <iframe id="mapFrame" name="mapFrame" src="/destination/to-map" width="484px;" height="320px;" frameborder="0"></iframe>
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


<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>





<script type="text/javascript">

    $(document).ready(function() {
        Metronic.initSlimScroll($(".scroller"));
        FormValidation.init("addScenic");
        $("#titleImgPre").hide();//隐藏预览封面图

        $('.timepicker-no-seconds').timepicker({
            autoclose: true,
            minuteStep: 1
        });

        $("#title").bind("blur",function(){
            findScenicInfo();
        });

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

    function findScenicInfo()
    {
        var title=$("#title").val();
        if(title==""){
            return;
        }
        $.ajax({
            url :'/destination/get-scenic-map-info?search='+title,
            type:'get',
            data:{},
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("获取景区详情失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    window.frames['mapFrame'].setMapSite(data.data.lng,data.data.lat);
                }else{
                    Main.errorTip("获取景区信息失败，请手动选取坐标");
                }
            }
        });
    }

    //添加景区
    function addScenic(){


        var lat=document.getElementById('mapFrame').contentWindow.document.getElementById("us3-lat").value;
        var lon=document.getElementById('mapFrame').contentWindow.document.getElementById("us3-lon").value;

        var desId=$("#desId").val();
        var title=$("#title").val();
        var titleImg=$("#titleImg").val();
        var intro=$("#intro").val();
        var beginTime=$("#beginTime").val();
        var endTime=$("#endTime").val();

        if(titleImg==''){
            Main.errorTip("封面图不允许为空");
            return;
        }


        $.ajax({
            url :'/destination/add-scenic',
            type:'post',
            data:{
                title:title,
                titleImg:titleImg,
                intro:intro,
                beginTime:beginTime,
                endTime:endTime,
                lat:lat,
                lon:lon,
                desId:desId
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加景区失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("添加景区地成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("添加景区地失败,错误信息:<br/>"+data.data);
                }
            }
        });
    }


</script>
</body>
</html>
