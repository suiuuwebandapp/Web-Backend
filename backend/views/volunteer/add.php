<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午4:57
 * Email: zhangxinmailvip@foxmail.com
 */

?>


<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/css/mobiscroll.custom-2.14.4.min.css"  />
<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/select2/select2_metro.css"
<link rel="stylesheet" type="text/css" href="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/uploadifive.css">



<style type="text/css">
    .uploadifive-button{
        background: #1bbc9b;
        color: #ffffff;
        border: none;
        width: 120px !important;
        height: 35px !important;
        line-height: 36px !important;
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
    #orgImgPre{
        margin-bottom: 10px;
        max-height: 50px;
    }
    .input-group  input.price_date{
        width: 119px;
    }
    .input-group  input.price:{
        width: 240px;
        border-right: none;
    }

</style>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">添加志愿产品</span>
                    <span class="caption-helper"> 添加志愿产品本信息 </span>
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
                                    <input type="text" id="title" name="title" value="" class="form-control" placeholder="请输入产品标题" maxlength="20" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <input type="hidden" id="titleImg"/><img id="titleImgPre"/><div id="titleQueue"></div>
                                <input id="titleUpload" name="file_upload" type="file">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">国家&nbsp;</label>
                            <div class="col-md-4 valdate">
                                <select id="countryId" name="countryIds" class="form-control select2" placeholder="请选择国家" required>
                                    <option value=""></option>
                                    <?php foreach($countryList as $country){ ?>
                                        <option value="<?= $country['id'] ?>"><?= $country['cname']."/".$country['ename'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">城市&nbsp;</label>
                            <div class="col-md-4 valdate">
                                <select id="cityId" name="cityIds" class="form-control select2" placeholder="请选择城市">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">分类</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="kind" name="kind" class="form-control" placeholder="请输入产品分类">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">年龄限制</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="ageInfo" name="ageInfo" class="form-control" placeholder="请输入年龄限制"  required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">团队人数</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="teamCount" name="teamCount" value="" class="form-control" placeholder="请输入团队人数" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">出发地点</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="beginSite" name="beginSite" value="" class="form-control" placeholder="请输入出发地点" maxlength="100" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">截止日期</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="endDate" class="form-control" placeholder="请输入截止日期" name="endDate" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">推荐理由</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea name="recommendInfo" id="recommendInfo" class="form-control" placeholder="请输入推荐理由"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">项目详情</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <button id="info" type="button" class="btn UEditor">&nbsp;&nbsp;编辑&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">预定说明</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea name="prepare" id="prepare" class="form-control" placeholder="请输入预定说明"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">行程简介</label>
                            <div class="valdate col-md-4">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea id="scheduleIntro" name="scheduleIntro"  class="form-control" placeholder="请输入行程简介"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">行程安排</label>
                            <div id="scheduleList" class="col-md-4">
                                <div class="valdate">
                                    <div class="input-icon right input-group">
                                        <textarea name="scheduleInfo" class="form-control" placeholder="请输入行程安排" ></textarea>
                                        <span class="input-group-btn"><button id="addSchedule" class="btn blue" type="button">+</button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">餐饮安排</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <button id="eat" type="button" class="btn UEditor">&nbsp;&nbsp;编辑&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">住宿安排</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <button id="hotel" type="button" class="btn UEditor">&nbsp;&nbsp;编辑&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">注意事项</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea id="note" name="note" class="form-control" placeholder="请输入注意事项"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">价格包含</label>
                            <div id="includeList" class="col-md-4">
                                <div class="valdate">
                                    <div class="input-icon right input-group">
                                        <input type="text" name="include" value="" class="form-control" placeholder="请输入价格包含内容" maxlength="30" />
                                        <span class="input-group-btn"><button id="addInclude" class="btn blue" type="button">+</button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">价格不包含</label>
                            <div id="unIncludeList" class="col-md-4">
                                <div class="valdate">
                                    <div class="input-icon right input-group">
                                        <input type="text" name="unInclude" value="" class="form-control" placeholder="请输入价格不包含内容" maxlength="30" />
                                        <span class="input-group-btn"><button id="addUnInclude" class="btn blue" type="button">+</button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">组织名称</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" id="orgName" name="orgName" value="" class="form-control" placeholder="请输入组织名称" maxlength="30" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">组织介绍</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <textarea id="orgInfo" name="orgInfo"  class="form-control" placeholder="请输入组织介绍" maxlength="30" aria-required="true" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">组织图片</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <input type="hidden" id="orgImg"/><img id="orgImgPre"/><div id="orgImgQueue"></div>
                                    <input id="orgImgUpload" name="file_upload" type="file">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">价格方案</label>
                            <div class="col-md-4 valdate" id="priceList">
                                <div class="input-icon right input-group">
                                    <i class="fa" style="z-index: 99;right: 80px"></i>
                                    <input type="text" name="price" value="" class="form-control price" placeholder="请输入价格" maxlength="30" required style="padding-right: 50px"/>
                                    <span class="input-group-addon">￥</span>
                                    <input type="text" name="date" value="" class="form-control price_date" placeholder="请输入天数" maxlength="30" required/>
                                    <span class="input-group-addon">天</span>
                                    <span class="input-group-btn"><button id="addPriceInfo" class="btn blue" type="button">+</button></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">服务日期</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input id="dateList" name="dateList" class="form-control" placeholder="设置可服务日期" value="" required=""/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">图片介绍</label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <input type="hidden" id="picList"/><div id="picQueue"></div>
                                    <input id="picUpload" name="file_upload" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;添加产品&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/admin/pages/scripts/form-validation.js?<?=time().rand(100,999)?>"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/js/mobiscroll.core.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/js/mobiscroll.util.datetime.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/js/mobiscroll.datetimebase.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/js/mobiscroll.datetime.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/mobiscroll/js/mobiscroll-2.14.4-crack.js"></script>




<?=\backend\widgets\UEditor::widget()?>

<script type="text/javascript">

    $(function () {
        $('#dateList').mobiscroll().calendar({
            theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
            lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
            display: 'modal',    // Specify display mode like: display: 'bottom' or omit setting to use default
            multiSelect: true,
            counter: true
        });
        $('#endDate').mobiscroll().calendar({
            theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
            lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
            display: 'modal',    // Specify display mode like: display: 'bottom' or omit setting to use default
            counter: true
        });
    });
</script>



<script type="text/javascript">

    $('.UEditor').click(function(){
        var html=$(this).attr("html");
        UEditorUtil.showUEditor(html,"html",this);
    });


    $(document).ready(function() {
        FormValidation.init("addVolunteerTrip");
        initUpload();
        initBtnEvent();
    });

    function initBtnEvent(){
        $(".select2").select2({
            'width':'426px'
        });
        $("#countryId").on("change", function (e) {
            getCityList();
        });

        $("#addSchedule").on("click",function(){
            addSchedule();
        });

        $("#addInclude").on("click",function(){
            addPriceInclude(1);
        });
        $("#addUnInclude").on("click",function(){
            addPriceInclude(0);
        });
        $("#addPriceInfo").on("click",function(){
            addPriceInfo();
        });
        $('.date-picker').datepicker({
            orientation: "left",
            autoclose: true,
            language: 'zh-CN'
        });
    }

    function initUpload(){
        $("#titleImgPre").hide();//隐藏预览封面图
        $('#titleUpload').uploadifive({
            'auto'             : true,
            'buttonText'       : '请选择封面图',
            'queueID'          : 'titleQueue',
            'uploadScript'     : '/upload/upload-title-img',
            'multi'            : false,
            'onUploadComplete' : function(file, data) {
                var datas= $.parseJSON(data);
                if(datas.status==1){
                    Main.successTip("上传图片成功");
                    $("#titleQueue").html("");
                    $("#titleImg").val(datas.data);
                    $("#titleImgPre").attr("src",datas.data);
                    $("#titleImgPre").show();
                }else{
                    $(file.queueItem).remove();
                    Main.errorTip("上传图片失败");
                }
            }
        });
        $("#orgImgPre").hide();//隐藏预览封面图
        $('#orgImgUpload').uploadifive({
            'auto'             : true,
            'buttonText'       : '请选择组织图片',
            'queueID'          : 'orgImgQueue',
            'uploadScript'     : '/upload/upload-title-img',
            'multi'            : false,
            'onUploadComplete' : function(file, data) {
                var datas= $.parseJSON(data);
                if(datas.status==1){
                    Main.successTip("上传图片成功");
                    $("#orgImgQueue").html("");
                    $("#orgImg").val(datas.data);
                    $("#orgImgPre").attr("src",datas.data);
                    $("#orgImgPre").show();
                }else{
                    $(file.queueItem).remove();
                    Main.errorTip("上传图片失败");
                }
            }
        });
        $('#picUpload').uploadifive({
            'auto'             : true,
            'buttonText'       : '请选列表图片',
            'queueID'          : 'picQueue',
            'uploadScript'     : '/upload/upload-title-img',
            'multi'            : true,
            'onUploadComplete' : function(file, data) {
                var datas= $.parseJSON(data);
                if(datas.status==1){
                    console.info(file.queueItem);
                    $(file.queueItem).attr("src",datas.data);
                    Main.successTip("上传图片成功");
                }else{
                    $(file.queueItem).remove();
                    Main.errorTip("上传图片失败");
                }
            }
        });
    }

    function addSchedule(){
        var html ='<div class="valdate">';
            html+='<div class="input-icon right input-group" style="margin-top: 10px">';
            html+='<textarea name="scheduleInfo" class="form-control" placeholder="请输入行程安排"></textarea>';
            html+='<span class="input-group-btn"><button class="btn blue" type="button" onclick="$(this).parent().parent().remove();">--</button></span>';
            html+='</div>';
            html+='</div>';

        $("#scheduleList").append(html);
    }

    function addPriceInclude(type) {

        var html='';
        if(type==1){
            html+='<div class="valdate">';
            html+='<div class="input-icon right input-group" style="margin-top: 10px">';
            html+='<input type="text" name="include" value="" class="form-control" placeholder="请输入价格包含内容" maxlength="30">';
            html+='<span class="input-group-btn"><button class="btn blue" type="button" onclick="$(this).parent().parent().remove();">--</button></span>';
            html+='</div>';
            html+='</div>';

            $("#includeList").append(html);
        }else{
            html+='<div class="valdate">';
            html+='<div class="input-icon right input-group" style="margin-top: 10px">';
            html+='<input name="unInclude" type="text" value="" class="form-control" placeholder="请输入价格不包含内容" maxlength="30">';
            html+='<span class="input-group-btn"><button class="btn blue" type="button" onclick="$(this).parent().parent().remove();">--</button></span>';
            html+='</div>';
            html+='</div>';

            $("#unIncludeList").append(html);
        }
    }

    function addPriceInfo(){
        var html ='<div class="valdate">';
        html+='<div class="input-icon right input-group" style="margin-top: 10px">';
        html+='<i class="fa" style="z-index: 99;right: 80px"></i>';
        html+='<input type="text" name="price" value="" class="form-control price" placeholder="请输入价格" maxlength="30" required style="padding-right: 50px"/>';
        html+='<span class="input-group-addon">￥</span>';
        html+='<input type="text" name="date" value="" class="form-control price_date" placeholder="请输入天数" maxlength="30" required/>';
        html+='<span class="input-group-addon">天</span>';
        html+='<span class="input-group-btn"><button onclick="$(this).parent().parent().remove()" class="btn blue" type="button">--</button></span>';
        html+='</div>';
        html+='</div>';

        $("#priceList").append(html);
    }



    function addVolunteerTrip(){

        var titleImg=$("#titleImg").val();
        var orgImg=$("#orgImg").val();
        var info=$("#info").attr("html");
        var eat=$("#eat").attr("html");
        var hotel=$("#hotel").attr("html");
        var includeList=new Array();
        var unIncludeList=new Array();
        var scheduleList=new Array();
        var picList=new Array();
        var priceList=new Array();

        $("#scheduleList textarea").each(function(){
            var val=$(this).val();
            if(val.length>0){
                scheduleList.push(val);
            }
        });
        $("#includeList input").each(function(){
            var val=$(this).val();
            if(val.length>0){
                includeList.push(val);
            }
        });
        $("#unIncludeList input").each(function(){
            var val=$(this).val();
            if(val.length>0){
                unIncludeList.push(val);
            }
        });
        $("#priceList div[class='input-icon right input-group']").each(function(){
            var price=$(this).find("input").eq(0).val();
            var day=$(this).find("input").eq(1).val();
            if(price!=''&&day!=''){
                var priceInfo=[price,day];
            }
            priceList.push(priceInfo);
        });

        $("#picQueue div[class='uploadifive-queue-item complete']").each(function(){
            var val=$(this).attr("src");
            if(val!=undefined&&val!=''){
                picList.push(val);
            }
        });

        if(titleImg==''){
            Main.errorTip("封面图不允许为空");
            return;
        }
        if(info==undefined||info==''){
            Main.errorTip("项目详情不允许为空");
            return;
        }
        if(eat==undefined||eat==''){
            Main.errorTip("餐饮安排不允许为空");
            return;
        }
        if(hotel==undefined||hotel==''){
            Main.errorTip("住宿安排不允许为空");
            return;
        }
        if(orgImg==''){
            Main.errorTip("组织图片不能为空");
            return;
        }


        if(picList.length==0){
            Main.errorTip("至少要有一个图片介绍");
            return;
        }

        if(priceList.length==0){
            Main.errorTip("至少要有一个价格方案");
            return;
        }
        var params=Main.getFormParams("#form_validate");
        params['info']=info;
        params['eat']=eat;
        params['hotel']=hotel;
        params['titleImg']=titleImg;
        params['orgImg']=orgImg;
        params['includeList']=includeList;
        params['unIncludeList']=unIncludeList;
        params['scheduleList']=scheduleList;
        params['picList']=picList;
        params['priceList']=priceList;

        $.ajax({
            url :'/volunteer/save',
            type:'post',
            cache:false,
            data:params,
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("添加志愿产品失败,未知系统异常");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.successTip("添加志愿产品成功");
                    Main.refresh();
                }else{
                    Main.errorTip("添加志愿产品失败,错误信息:"+datas.data);
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


