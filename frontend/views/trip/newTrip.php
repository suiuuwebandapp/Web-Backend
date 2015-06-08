<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/27
 * Time : 下午6:36
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/time-picki/css/timepicki.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">
<link rel="stylesheet" type="text/css" href="/assets/pages/trip/new-trip.css"/>


<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/js/squid.js"></script>
<script type="text/javascript" src="/assets/plugins/time-picki/js/timepicki.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>

<!--编辑切换-->
<script  type="text/javascript" src="/assets/js/xcbjy.js"></script>


<div class="bjy clearfix" id="bjy-box">
    <ul id="bz">
        <li class="active"><a href="javascript:;">上传封面</a></li>
        <li><a href="javascript:;">位置地图</a></li>
        <li><a href="javascript:;">上传图片</a></li>
        <li><a href="javascript:;">服务/价格</a></li>
        <li><a href="javascript:;">详情描述</a></li>
        <li><a href="javascript:;" id="finishTab">完成</a></li>
    </ul>
    <a href="javascript:;" class="bjy-prev" id="bjy-prev">上一步</a>
    <a href="javascript:;" class="bjy-next" id="bjy-next">下一步</a>
    <div class="bjy-list clearfix">
        <!--step1-->
        <div class="bjy-bj1 bjy-bj" style=" display:block;">
            <div class="content">
                <p>
                    <span class="spn_title">标题</span>
                    <span class="form_tip" id="titleTip"></span>
                </p>
                <input type="text" value="" id="title" step="1">
                <p>
                    <span class="spn_title">随游简介</span>
                    <span class="form_tip" id="introTip"></span>
                </p>
                <textarea class="jianjie" id="intro"></textarea>
                <div>
                    <p>
                        <span class="spn_title">封面</span>
                        <span id="titleImgTip" class="form_tip"></span>
                    </p>
                    <div id="divCardFront" class="imgPic">
                        <img src="" id="titleImg" style="display: none" class="showImg"/>
                        <p class="p_chose_title_img">点击上传封面图</p>
                    </div>

                    <input id="titleImgFile" type="file"/>

                    <div id="frontQueue" class="queue"></div>
                    <input type="hidden" id="tripTitleImg"/>
                    <p class="upload_tip">上传文件大小请不能大于2M，支持格式png、jpg、jpeg</p> <br/>
                    <input type="button" value="上&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;传" class="schuan" id="uploadAll">
                </div>
            </div>
        </div>
        <!--step1 End-->

        <!--step2 begin-->
        <div class="bjy-bj2 bjy-bj">
            <p>
               <span class="spn_title">坐标</span>
               <span class="form_tip" id="countryTip"></span>
            </p>
            <select id="countryId" name="country" class="select2" placeholder="国家" required>
                <option value=""></option>
                <?php foreach ($countryList as $c) { ?>
                    <option value="<?= $c['id'] ?>"><?= $c['cname'] . "/" . $c['ename'] ?></option>
                <?php } ?>
            </select>
            <select id="cityId" name="city" class="select2" placeholder="城市" required></select>
            <p>
                <span class="spn_title">景点名称</span>
                <span class="form_tip" id="scenicTip"></span>
            </p>
            <div id="scenicList">
                <div class="jing">
                    <input type="text" placeholder="景点" onfocus="loadLocation(this)" onblur="searchLocation(this)" />
                    <a id="addScenic" href="javascript:;" class="add"></a>
                </div>
            </div>

            <div class="map">
                <iframe id="mapFrame" name="mapFrame" src="/google-map/to-map" width="350px" height="330px;" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>
        <!--step2 end-->

        <!--step3 begin-->
        <div class="bjy-bj3 bjy-bj">
            <div id="upload_div" class="upload_div">
                <a id="uploadPic"><img src="/assets/images/addPic.gif" width="205" height="115"></a>
            </div>
            <div>
                <input type="file" id="picFile"/>
            </div>
        </div>
        <!------step3  end-->

        <!----step4 ------>
        <div class="bjy-bj4 bjy-bj">
            <div class="bj4-div">
                <p>
                    <span>基本价格</span>
                    <span class="form_tip" id="basePriceTip"></span>
                </p>
                <p class="sect">
                    <input type="text" value="" id="basePrice" />
                    <select name="" class="serviceSelect" id="basePriceType">
                        <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_PERSON?>">每人</option>
                        <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?>">每次</option>
                    </select>
                </p>
                <span>价格包括（选填）</span>
                <div id="include_detail">
                    <p><input type="text" value="" class="text2"><a href="javascript:addDetail(true);" class="add"></a></p>
                </div>
                <span>价格不包括（选填）</span>
                <div id="uninclude_detail">
                    <p><input type="text" value="" class="text2"><a href="javascript:addDetail(false);" class="add"></a></p>
                </div>
                <p class="mixi"><font>价格明细</font></p>
                <div class="bj4-main" >
                    <p>
                        <span>人数上限</span>
                        <span class="form_tip" id="peopleCountTip"></span>
                    </p>
                    <input type="text" placeholder="你最多可以接待多少人呢" class="sx" id="peopleCount">
                    <div id="step_div_content">
                        <P>
                            <span>优惠价格（选填）</span>
                            <span class="form_tip" id="stepTip"></span>
                        </P>
                        <div id="stepDiv">
                            <p>
                                <input type="text" value="" class="step_people"><em>人至</em>
                                <input type="text" value="" class="step_people"><em>人</em>
                                <input type="text" value="" class="step_price"><em>RMB</em>
                                <a href="javascript:;" id="addStepPrice" class="add"></a>
                            </p>
                        </div>
                    </div>
                    <p>
                        <span style="width: 180px;">单项服务及价格（选填）</span>
                        <span class="form_tip" id="servicePriceTip"  style="width: 220px !important;"></span>
                    </p>
                    <div class="creat clearfix">
                        <dl id="stepDl">
                            <dt><span>服务</span><span>价格</span><span>单位</span></dt>
                            <dd style="z-index:14">
                                <input type="text" value="" class="m0-input">
                                <input type="text" value="" class="service_price_step">
                                <div class="sect">
                                    <select name="" class="serviceSelect">
                                        <option value="1">一人</option>
                                        <option value="0">一次</option>
                                    </select>
                                </div>
                                <a id="addServicePrice" href="javascript:;" class="add"></a>
                            </dd>
                        </dl>

                    </div>
                    <div class="start-time clearfix">
                        <dl>
                            <dt><span>可提供服务时间</span> <span class="form_tip" id="serviceTimeTip"></span></dt>
                            <dd>
                                <input type="text" id="beginTime" class="timepicker_start" placeholder="请选择开始时间">
                                <b class="service_time_tip">至</b>
                                <input type="text" id="endTime" class="timepicker_end" placeholder="请选择结束时间">
                            </dd>
                        </dl>
                    </div>
                    <div class="times clearfix">
                        <dl>
                            <dt><span>随游时长</span> <span class="form_tip" id="tripLongTip"></dt>
                            <dd>
                                <input type="text" value="" class="trip_time" id="tripLong">
                                <div class="sect">
                                    <select name="" id="tripKind" data-enabled="false" class="serviceSelect">
                                        <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_HOUR?>">小时</option>
                                        <option value="<?=\common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_DAY?>">天</option>
                                    </select>
                                </div>
                                <a href="###" class="jian"></a>
                            </dd>
                        </dl>

                    </div>
                </div>
            </div>
        </div>

        <!---step4--end-->


        <!---step5- begin-->
        <div class="bjy-bj5 bjy-bj clearfix"  id="bjy">
            <span>详情介绍<b class="form_tip" id="infoTip"></b></span>
            <textarea id="info"></textarea>
            <span>添加行程标签<b class="form_tip" id="tagsTip"></b></span>
            <div class="biaoqian clearfix">
                <ul class="clearfix" id="tagsUl">
                    <!--<li class="active-bj">家庭</li>-->
                    <?php foreach($tagList as $tag){ ?>
                    <li><?=$tag?></li>
                    <?php }?>
                </ul>
                <span>随游亮点（选填）</span>
                <div id="highlight_div">
                    <p><input type="text" value=""><a href="javascript:addHighlight();" class="add"></a></p>
                </div>
                <input type="button" class="btn yulan" value="预览" id="preview">
                <input type="button" class="btn sure" value="立即发布" id="tripFinish">
            </div>
        </div>
        <!---step5 end-->
        <div class="bjy-bj6 bjy-bj">6</div>
    </div>
</div>
<!--发布随游 end-->


<script type="text/javascript">
    $(document).ready(function(){

        /*添加标签*/
        xcbjy();
        bz('bjybox','bjy-bj');
        /*添加标签*/

        $("#bjy-prev").hide();

        //初始化国家，城市
        $(".select2").select2({
            'width':'350px',
            containerCss: {
                'margin-bottom':'20px'
            },
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });

        $(".serviceSelect").select2({
            width:'100px',
            allowClear: true,
            dropdownCssClass:'service_price'
        });


        //绑定获取城市列表
        $("#countryId").on("change", function () {
            $("#countryTip").html("");
            getCityList();
        });
        $("#cityId").on("change", function () {
            if($("#cityId").val()!=""){
                $("#cityTip").html("");
            }
        });

        initUploadfive();

        //初始化上传身份证等功能
        $(".p_chose_title_img").bind("click", function () {
            $("#uploadAll").val("上     传");
            $("#tripTilteImg").val("");
            var file = $("#uploadifive-titleImgFile input[type='file']").last();
            $(file).click();
        });

        $("#uploadPic").bind("click", function () {
            var file = $("#uploadifive-picFile input[type='file']").last();
            $(file).click();
        });

        //绑定上传事件
        $("#uploadAll").bind("click", function () {
            if ($("#titleImg").attr("src") == "") {
                $("#titleImgTip").html("请选择随游封面图");
                return;
            }
            $("#titleImgTip").html("");
            $('#titleImgFile').uploadifive('upload');
            $("#uploadAll").val("正在上传，请稍后...");
        });

        $("#addScenic").bind("click",function(){
            addScenic();
        });

        $('.timepicker_start').timepicki({
            format_output: function(tim, mini, meri) {
                return tim + ":" + mini + " " + meri;
            }
        });
        $('.timepicker_end').timepicki({
            custom_classes:'time_end',
            format_output: function(tim, mini, meri) {
                return tim + ":" + mini + " " + meri;
            }
        });

        $("#addServicePrice").bind("click",function(){
            addServicePrice();
        });
        $("#addStepPrice").bind("click",function(){
            addStepPrice();
        });


        $("#preview").bind("click",function(){
            saveTrip(2);
        });
        $("#tripFinish").bind("click",function(){
            saveTrip(1);
        });

        $("#basePriceType").bind("change",function(){
            if($(this).val()==TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT){
                $("#step_div_content").hide();
            }else{
                $("#step_div_content").show();
            }
        });

        initValidate();
        initTouchSpin();

    });

    //初始化选择器
    function initTouchSpin()
    {
        $("#peopleCount").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000
        });

        $("#basePrice").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000000
        });

        $(".step_people").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000
        });
        $(".step_price").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000000
        });

        $(".service_price_step").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000000
        });

        $("#tripLong").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000
        });
    }

    function initValidate()
    {
        $("#title").bind("blur",function(){
            if($("#title").val()==""){$("#titleTip").html("请输入随游标题");}else{$("#titleTip").html("");};
        });
        $("#intro").bind("blur",function(){
            if($("#intro").val()==""){$("#introTip").html("请输入随游简介");}else{$("#introTip").html("");};
        });

        $("#basePrice").bind("focus",function(){
            $("#basePriceTip").html("");
        });

        $("#peopleCount").bind("focus",function(){
            $("#peopleCountTip").html("");
        });
        $("#beginTime").bind("focus",function(){
            $("#serviceTimeTip").html("");
        });
        $("#tripLong").bind("focus",function(){
            $("#tripLongTip").html("");
        });

        $("#info").bind("focus",function(){
            $("#infoTip").html("");
        });
        $("#tagsUl li").bind("click",function(){
            $("#tagsTip").html("");
        });


    }


    //保存随游
    function saveTrip(saveType) {

        var title = $("#title").val();
        var titleImg = $("#tripTitleImg").val();
        var intro = $("#intro").val();

        var countryId = $("#countryId").val();
        var cityId = $("#cityId").val();
        var scenicList = new Array();
        var picList = new Array();

        var basePrice = $("#basePrice").val();
        var basePriceType=$("#basePriceType").val();
        var peopleCount = $("#peopleCount").val();
        var stepPriceList = new Array();
        var serviceList = new Array();
        var includeDetailList=new Array();
        var unIncludeDetailList=new Array();
        var beginTime = $("#beginTime").val();
        var endTime = $("#endTime").val();
        var tripLong = $("#tripLong").val();
        var tripKind=$("#tripKind").val();

        var info = $("#info").val();
        var tagList = new Array();
        var highlightList=new Array();


        var error = false;



        //TAB 1验证
        if (title == "") {
            $("#titleTip").html("请输入随游标题");
            error = true;
        }
        if (intro == "") {
            $("#introTip").html("请输入随游简介");
            error = true;
        }
        if (titleImg == "") {
            $("#titleImgTip").html("请选择随游封面并上传");
            error = true;
        }
        if (error) {
            selectTab(1);
            return;
        }

        //TAB 2验证
        if (countryId == "" || cityId == "") {
            $("#countryTip").html("请选择国家和城市");
            error = true;
        }
        $("#scenicList input").each(function () {
            var title=$(this).val();
            var lon = $(this).attr("lon");
            var lat = $(this).attr("lat");
            if (lon != "" && lon != undefined && lat != "" && lat != undefined) {
                var scenic = [title,lon, lat];
                scenicList.push(scenic);
            }
        });
        if (scenicList.length == 0) {
            $("#scenicTip").html("至少选择一个景区");
            error = true;
        }
        if (error) {
            selectTab(2);
            return;
        }


        //TAB 3验证
        var size = $("#upload_div a[class='imgs'] img").size();
        if (size == 0) {
            selectTab(3);
            Main.showTip("请至少上传一张图片");
            return;
        }

        //TAB 4验证
        $("#upload_div a[class='imgs'] img").each(function () {
            picList.push($(this).attr("src"));
        });
        if (basePrice == "") {
            $("#basePriceTip").html("请输入基础价格");
            error = true;
        }
        if (peopleCount == "") {
            $("#peopleCountTip").html("请输入最多可接待人数");
            error = true;
        }
        if (beginTime == "" || endTime == "") {
            $("#serviceTimeTip").html("请选择可提供服务时间");
            error = true;
        }
        if (tripLong == "") {
            $("#tripLongTip").html("请输入随游时长");
            error = true;
        }

        $("#stepDiv p").each(function () {
            var ipts = $(this).find("input");
            var min = $(ipts).eq(0).val();
            var max = $(ipts).eq(1).val();
            var price = $(ipts).eq(2).val();
            if (min != "" && max != "" && price != "") {
                var stepPrice = [min, max, price];
                stepPriceList.push(stepPrice);
            }
        });
        $("#stepDl dd").each(function () {
            var ipts = $(this).find("input");
            var service = $(ipts).eq(0).val();
            var price = $(ipts).eq(1).val();
            var unit = $(this).find("select").val();
            if (service != "" && price != "" && unit != "") {
                var serviceInfo = [service, price, unit];
                serviceList.push(serviceInfo);
            }
        });
        $("#include_detail input").each(function(){
            var name=$(this).val();
            if(name!=""){
                includeDetailList.push(name);
            }
        });
        $("#uninclude_detail input").each(function(){
            var name=$(this).val();
            if(name!=""){
                unIncludeDetailList.push(name);
            }
        });
        if (error) {
            selectTab(4);
            return;
        }

        //TAB 5 验证
        if (info == "") {
            $("#infoTip").html("请输入详情介绍");
            error=true;
        }
        $("#tagsUl li").each(function () {
            if ($(this).hasClass("active-bj")) {
                tagList.push($(this).html());
            }
        });

        $("#highlight_div input").each(function(){
            var value=$(this).val();
            if(value!=""){
                highlightList.push(value);
            }
        });
        if (tagList.length == 0) {
            $("#tagsTip").html("至少要选择一个标签哦~");
            error=true;
        }
        if (error) {
            selectTab(5);
            return;
        }

        $.ajax({
            url :'/trip/save-trip',
            type:'post',
            data:{
                title:title,
                titleImg:titleImg,
                intro:intro,
                countryId:countryId,
                cityId:cityId,
                scenicList:scenicList,
                picList:picList,
                basePrice:basePrice,
                basePriceType:basePriceType,
                peopleCount:peopleCount,
                stepPriceList:stepPriceList,
                serviceList:serviceList,
                includeDetailList:includeDetailList,
                unIncludeDetailList:unIncludeDetailList,
                beginTime:beginTime,
                endTime:endTime,
                tripLong:tripLong,
                tripKind:tripKind,
                info:info,
                tagList:tagList,
                highlightList:highlightList,
                status:saveType

            },
            beforeSend:function(){
                $("#preview").attr("disabled","disabled");
            },
            error:function(){
                $("#preview").removeAttr("disabled");
                Main.showTip("保存随游失败");
            },
            success:function(data){
                $("#preview").removeAttr("disabled");
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href='/view-trip/info?trip='+data.data.tripId;
                }else{
                    Main.showTip("保存随游失败");
                }
            }
        });



    }


    //切换TAB
    function selectTab(count)
    {
        $("#bjy-box ul li").eq(count-1).click();
    }

    //添加阶梯价格
    function addStepPrice(){
        var html='<p><input type="text" value="" class="step_people"><em>人至</em>' +
                    '<input type="text" value="" class="step_people"><em>人</em>' +
                    '<input type="text" value="" class="step_price"><em>RMB</em>' +
                    '<a href="javascript:;" onclick="removeStepPrice(this)" class="jian"></a>' +
                '</p>';
        $("#stepDiv").append(html);
        $(".step_people").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000
        });
        $(".step_price").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000000
        });
    }

    function removeStepPrice(obj){
        $(obj).parent("p").remove();
    }
    //添加专项服务
    function addServicePrice(){
        var html=' <dd style="z-index:11"><input type="text" value="" class="m0-input"><input type="text" value="" class="service_price_step">' +
            '<div class="sect"><select id="test" class="serviceSelect"><option value="1">一人</option><option value="0">一次</option></select>' +
            '</div><a href="javascript:;" onclick="removeServicePrice(this)" class="jian"></a>' +
            '</dd>';
        $("#stepDl").append(html);
        initSelect();
        $(".service_price_step").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000000
        });

    }

    //移除专项服务
    function removeServicePrice(obj){
        $(obj).parent("dd").remove();
    }

    //添加随游明细 type=true include
    function addDetail(type){
        var html='<p><input type="text" value="" class="text2"><a href="javascript:;" onclick="removeDetail(this)" class="jian"></a></p>';
        if(type){
            $("#include_detail").append(html);
        }else{
            $("#uninclude_detail").append(html);
        }
    }

    //删除明细
    function removeDetail(obj){
        $(obj).parent().remove();
    }

    //添加亮点
    function addHighlight(){
        var html='<p><input type="text" value="" class="text2"><a href="javascript:;" onclick="removeHighlight(this)" class="jian"></a></p>';
        $("#highlight_div").append(html);
    }
    //移除亮点
    function removeHighlight(obj){
        $(obj).parent().remove();
    }

    //动态初始化SELECT
    function initSelect(){
        $(".serviceSelect").each(function(){
            try{
                $(this).select2({
                    width:'100px',
                    allowClear: true,
                    dropdownCssClass:'service_price'
                });
            }catch(e){}
        });
    }

    //加载地图
    function loadLocation(obj){
        $("#scenicTip").html("");
        var lon=$(obj).attr("lon");
        var lat=$(obj).attr("lat");


        if(lon==''||lat==''){
            findScenicInfo(obj);
        }
    }
    //搜索地图
    function searchLocation(obj){
        $("#scenicTip").html("");
        var title=$(obj).attr("title");
        var name=$(obj).val();

        if(title!=name){
            findScenicInfo(obj);
        }
    }



    //获取景区详情
    function findScenicInfo(obj) {
        var name=$(obj).val();
        if(name==""){
            return;
        }
        $.ajax({
            url :'/google-map/search-map-info?search='+name,
            type:'get',
            data:{},
            beforeSend:function(){
            },
            error:function(){
                Main.showTip("获取景区详情失败,未知系统异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    $(obj).attr("lon",data.data.lng);
                    $(obj).attr("lat",data.data.lat);
                    $(obj).attr("title",name);

                    window.frames['mapFrame'].setMapSite(data.data.lng,data.data.lat);
                }else{
                    Main.showTip("获取景区信息失败，请手动选取坐标");
                }
            }
        });
    }

    //添加景区
    function addScenic(){
        var html='<div class="jing"><input type="text" placeholder="景点" onfocus="loadLocation(this)" onblur="searchLocation(this)" /><a href="javascript:;" onclick="removeScenic(this)" class="remove"></a></div>';
        $("#scenicList").append(html);
        //同时删除选中坐标
    }
    //删除景区
    function removeScenic(obj){
        $(obj).parent().remove();
    }



    //初始化封面图上传插件
    function initUploadfive(){
        $('#titleImgFile').uploadifive({
            'auto': false,
            'queueID': 'frontQueue',
            'uploadScript': '/upload/upload-trip-title-img',
            'multi': false,
            'dnd': false,
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#tripTitleImg").val(datas.data);
                    $("#titleImgTip").html("");
                    $("#uploadAll").val("上传成功！");
                } else {
                    $("#uploadAll").val("上传失败，请稍后重试。。。");
                }
            },
            onSelect:function(){
                //初始化预览图片
                $("#uploadifive-titleImgFile input[type='file'][id!='titleImgFile']").last().uploadPreview({
                    Img: "titleImg",
                    Width: 120,
                    Height: 120,
                    ImgType: [
                        "jpeg", "jpg", "png"
                    ], Callback: function () {
                        $("#titleImg").show();
                        $("#titleImg").unbind("click");
                        $(".p_chose_title_img").hide();
                        $("#titleImg").bind("click", function () {
                            $(".p_chose_title_img").click();
                        });
                    }
                });
            },
            onInit:function(){
                $("#uploadifive-titleImgFile input[type='file'][id!='titleImgFile']").last().uploadPreview({
                    Img: "titleImg",
                    Width: 120,
                    Height: 120,
                    ImgType: [
                        "jpeg", "jpg", "png"
                    ], Callback: function () {
                        $("#titleImg").show();
                        $("#titleImg").unbind("click");
                        $(".p_chose_title_img").hide();
                        $("#titleImg").bind("click", function () {
                            $(".p_chose_title_img").click();
                        });
                    }
                });
            }
        });

        $('#picFile').uploadifive({
            'auto': true,
            'queueID': 'frontQueue',
            'uploadScript': '/upload/upload-trip-img',
            'multi': false,
            'dnd': false,
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                var span=$("#upload_div span[picName='"+file.name+"']");
                var img=$(span).next().next();
                if (datas.status == 1) {
                    $(span).remove();
                    $(img).attr("src",datas.data);
                } else {
                    $("#span").html("上传失败，请重试");
                }
                var size=$("#upload_div a[class='imgs']").size();
                if(size>=10){
                    $("#uploadPic").hide();
                }
            },
            'onProgress'   : function(file, e) {
                var size=$("#upload_div span[picName='"+file.name+"']").size();
                if(size==0){
                    var html='<a href="#" class="imgs"><span class="upload_show_info" picName="'+file.name+'">正在上传...</span><span class="delet" onclick="removePic(this)"></span><img/></a>';
                    $("#upload_div").prepend(html);
                }
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                }
                //file.queueItem.find('.fileinfo').html(' - ' + percent + '%');
                //file.queueItem.find('.progress-bar').css('width', percent + '%');
            }
        });
    }

    function removePic(obj){
        $(obj).parent().remove();
        var size=$("#upload_div a[class='imgs']").size();
        if(size>=10){
            $("#uploadPic").hide();
        }
    }


    //级联获取城市列表
    function  getCityList(){
        var countryId=$("#countryId").val();
        if(countryId==""){
            return;
        }
        $("#countryTip").html("");
        $("#cityId").empty();

        $("#cityId").append("<option value=''></option>");
        $("#cityId").val("").trigger("change");
        $.ajax({
            url :'/country/find-city-list',
            type:'post',
            data:{
                countryId:countryId,
                _csrf: $('input[name="_csrf"]').val()

            },
            error:function(){
                $("#cityTip").html("获取城市列表失败");
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    var html = "";
                    for(var i=0;i<datas.data.length;i++){
                        var city=datas.data[i];
                        html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                    }
                    $("#cityId").append(html);
                }else{
                    $("#cityTip").html("获取城市列表失败");
                }
            }
        });
    }



</script>