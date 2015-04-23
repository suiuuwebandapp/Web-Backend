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
                    添加目的地
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
                                        <label class="col-md-3 control-label">标题<span class="required">*</span></label>
                                        <div class="col-md-7 valdate">
                                            <div class="input-icon right">
                                                <i class="fa"></i>
                                                <input type="text" id="title" value="" class="form-control" placeholder="请输入文章标题" maxlength="20"  required/>
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
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">国家&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <select id="countryId" name="countryIds" class="form-control muti_select" placeholder=" 请选择国家"  required>
                                                <option value=""></option>
                                                <?php foreach($countryList as $country){ ?>
                                                    <option value="<?= $country['id'] ?>"><?= $country['cname']."/".$country['ename'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">城市&nbsp;</label>
                                        <div class="col-md-7 valdate">
                                            <select id="cityId" name="cityIds" class="form-control muti_select" placeholder=" 请选择城市"  required>
                                                <option value=""></option>
                                            </select>
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
        FormValidation.init("addDes");
        $("#titleImgPre").hide();//隐藏预览封面图
        $(".muti_select").select2();
        $("#countryId").on("change", function (e) {
            getCityList();
        })
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
                data=eval("("+data+")");
                Main.hideWait();
                if(data.status==1){
                    Main.successTip("添加目的地成功");
                    $("#modal_close").click();
                    Main.refrenshTable();
                }else{
                    Main.errorTip("添加目的地失败,错误信息:<br/>"+datas.data);
                }
            }
        });
    }


    function  getCityList(){
        var countryId=$("#countryId").val();
        if(countryId==""){
            return;
        }
        $("#cityId").empty();
        $("#cityId").append("<option value=''></option>");
        $("#cityId").val("").trigger("change");
        $.ajax({
            url :'/country/find-city-list',
            type:'post',
            data:{
                countryId:countryId
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("获取城市列表失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    var html = "";
                    for(var i=0;i<datas.data.length;i++){
                        var city=datas.data[i];
                        html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                    }
                    $("#cityId").append(html);
                }else{
                    Main.errorTip("获取城市列表失败,错误信息:<br/>"+datas.data);
                }
            }
        });
    }

</script>
</body>
</html>
