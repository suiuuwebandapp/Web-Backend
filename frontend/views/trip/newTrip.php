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
<link rel="stylesheet" type="text/css" href="/assets/plugins/imgAreaSelect/css/imgareaselect-default.css"/>


<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/js/squid.js"></script>
<script type="text/javascript" src="/assets/plugins/time-picki/js/timepicki.js"></script>
<script type="text/javascript"
        src="/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.base.js"></script>

<!--编辑切换-->
<script type="text/javascript" src="/assets/js/xcbjy.js"></script>




<div class="bjy clearfix" id="bjy-box">
    <ul id="bz">
        <li class="active"><a href="javascript:;">上传封面</a></li>
        <li><a href="javascript:;">位置信息</a></li>
        <li><a href="javascript:;">添加照片</a></li>
        <li><a href="javascript:;">设置价格</a></li>
        <li><a href="javascript:;">详情描述</a></li>
    </ul>
    <div class="bjy-list clearfix">

        <!--step1-->
        <div class="bjy-bj1 bjy-bj" style="display: block;">
            <span class="spn_title">随游名称</span>
            <span class="form_tip" id="titleTip"></span>
            <input id="title" class="name" type="text" placeholder="清晰且具有描述性">

            <span class="spn_title">封面</span>
            <span id="titleImgTip" class="form_tip"></span>

            <div id="divCardFront" class="pic fPic">
                <img src="" id="titleImg" style="display: none" class="showImg"/>

                <p class="p_chose_title_img">点击上传封面图</p>
            </div>

            <input id="titleImgFile" type="file" style="display: none"/>

            <p class="upload_tip">上传文件大小请不能大于2M，支持格式png、jpg、jpeg</p> <br/>

            <!--step1 提示-->
            <div class="bjyPro bj1Pro01" style="display: none;">
                <h2 class="tit bgGreen">随游名称</h2>

                <p>给随游起一个好名字是让游客认识您的随游的重要途径</p>

                <p class="p2">示列：法国美食文化半日慢行</p>
            </div>
            <div class="bjyPro bj1Pro02" style="display: none;">
                <h2 class="tit bgGreen">封面图</h2>

                <p>封面图会显示在搜索列表页面及推荐页面，好的封面图会为您的随游引来更多关注。</p>
            </div>
        </div>
        <!--step1 End-->

        <!--step2 begin-->
        <div class="bjy-bj2 bjy-bj">
            <h2 class="titles">添加您的随游所涉及的位置地点，帮助旅行者更好的作出决策</h2>
            <select id="countryId" name="country" class="select2" placeholder="国家" required>
                <option value=""></option>
                <?php foreach ($countryList as $c) { ?>
                    <option value="<?= $c['id'] ?>"><?= $c['cname'] . "/" . $c['ename'] ?></option>
                <?php } ?>
            </select>
            <select id="cityId" name="city" class="select2" placeholder="城市" required></select>

            <p class="title_p">
                <span class="spn_title">景点名称</span>
                <span class="form_tip" id="scenicTip"></span>
            </p>

            <div id="scenicList" class="jings clearfix">
                <div class="jing">
                    <input type="text" placeholder="景点名称" onfocus="NewTrip.loadLocation(this)"
                           onblur="NewTrip.searchLocation(this)">
                    <a id="addScenic" href="javascript:;" class="add"></a>
                </div>
            </div>
            <div class="map">
                <iframe id="mapFrame" name="mapFrame" src="/google-map/to-map" width="440px" height="330px;"
                        frameborder="0" scrolling="no"></iframe>
            </div>

            <div class="bjyPro bj2Pro01">
                <h2 class="tit bgGreen">添加地点</h2>

                <p>在地图上添加随游中会涉及到的标志性地点，让位置信息更加丰富</p>

                <p class="p2">示列：大笨钟 自由女神像</p>

            </div>
        </div>
        <!--step2 end-->

        <!--step3 begin-->
        <div class="bjy-bj3 bjy-bj">
            <h2 class="titles">照片是旅行者在预定时最重要的参考依据，好的照片会让你的随游看起来更加精彩</h2>

            <div id="upload_div" class="upload_div clearfix">
                <a id="uploadPic" class="imgs"><img src="/assets/images/addPic.gif"></a>
            </div>
            <p>这里上传的照片都会显示在随游详情页的最重要位置，请好好挑选照片上传吧！</p>

            <p>有您自己上镜的照片会让随游的可信度更高哦！</p>

            <div>
                <input type="file" id="picFile" style="display: none"/>

                <div id="frontQueue" class="queue"></div>
                <input type="hidden" id="tripTitleImg"/>
            </div>
        </div>
        <!------step3  end-->

        <!----step4 ------>
        <div class="bjy-bj4 bjy-bj">
            <h2 class="titles">设置一个能体现随游的价值和好客之道的价格</h2>

            <div class="price1 clearfix">
                <span class="title">基本价格</span>
                <span class="form_tip" id="basePriceTip"></span>

                <p class="sect">
                    <input type="text" class="text01" value="" id="basePrice"/>
                    <select name="" class="serviceSelect" id="basePriceType">
                        <option value="<?= \common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_PERSON ?>">每人</option>
                        <option value="<?= \common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT ?>">每次</option>
                    </select>
                </p>


                <span>价格包括（选填）</span>

                <div id="include_detail">
                    <p><input type="text" value="" class="text2"><a href="javascript:;"
                                                                    onclick="NewTrip.addDetail(true)" class="add"></a>
                    </p>
                </div>
                <div>
                    <p class="detail_title">常用标签：</p>
                    <b class="detail_tags" type="include">陪同讲解</b>
                    <b class="detail_tags" type="include">随行翻译</b>
                    <b class="detail_tags" type="include">包车费用</b>
                    <b class="detail_tags" type="include">小费</b>
                    <b class="detail_tags" type="include">随友交通费用</b>
                </div>
                <span>价格不包括（选填）</span>

                <div id="uninclude_detail">
                    <p><input type="text" value="" class="text2"><a href="javascript:;"
                                                                    onclick="NewTrip.addDetail(false)" class="add"></a>
                    </p>
                </div>
                <div>
                    <p class="detail_title">常用标签：</p>
                    <b class="detail_tags" type="uninclude">门票费用</b>
                    <b class="detail_tags" type="uninclude">交通费用</b>
                    <b class="detail_tags" type="uninclude">住宿</b>
                    <b class="detail_tags" type="uninclude">餐饮费用</b>
                    <b class="detail_tags" type="uninclude">小费</b>
                    <b class="detail_tags" type="uninclude">接送机</b>
                    <b class="detail_tags" type="uninclude">其他未提及费用</b>
                </div>


                <div class="bjyPro bj4Pro01">
                    <h2 class="tit bgGreen">基本价格</h2>

                    <p>你可以通过设置低廉的价格吸引到头一批游客从而积攒好评，之后可以适当提高价格</p>
                </div>


            </div>

            <p class="mixi"><font>价格明细</font></p>

            <div class="bj4-main">
                <div class="price2">
                    <span>人数上限</span>
                    <span class="form_tip" id="peopleCountTip"></span>
                    <input type="text" placeholder="你最多可以接待多少人呢" class="sx" id="peopleCount">

                    <div id="step_div_content">
                        <span>优惠价格（选填）</span>
                        <span class="form_tip" id="stepTip"></span>

                        <div id="stepDiv">
                            <p>
                                <input type="text" value="" class="step_people"><b>人</b><em>&nbsp;至&nbsp;</em>
                                <input type="text" value="" class="step_people"><b>人</b>
                                <input type="text" value="" class="step_price"><b>RMB</b>
                                <a href="javascript:;" id="addStepPrice" class="add"></a>
                            </p>
                        </div>
                    </div>
                    <div class="bjyPro bj4Pro02">
                        <h2 class="tit bgGreen">优惠价格</h2>

                        <p>设置多人预订优惠价格，吸引旅行者团体预订。</p>
                    </div>
                </div>
                <p>
                    <span style="width: 180px;">附加服务及价格（选填）</span>
                    <span class="form_tip" id="servicePriceTip" style="width: 220px !important;"></span>
                </p>

                <div class="creat clearfix">
                    <dl id="stepDl">
                        <dt><span class="step_title_span">服务</span><span
                                class="step_title_span">价格</span><span>单位</span></dt>
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
                    <div class="bjyPro bj4Pro03">
                        <h2 class="tit bgGreen">可选服务</h2>

                        <p>在额外的随友服务项目之外可供游客选择的附加服务</p>
                    </div>
                </div>
                <div class="start-time clearfix">
                    <dl class="clearfix">
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
                                    <option value="<?= \common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_HOUR ?>">小时
                                    </option>
                                    <option value="<?= \common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_DAY ?>">天
                                    </option>
                                </select>
                            </div>
                            <a href="###" class="jian"></a>
                        </dd>
                    </dl>

                </div>
            </div>
        </div>
        <!---step4--end-->

        <!---step5- begin-->
        <div class="bjy-bj5 bjy-bj clearfix" id="bjy">
            <div class="bjyPro bj5Add screens" id="addTagDiv">
                <h2 class="tit bgGreen">添加标签</h2>
                <input id="cusTagTitle" type="text" maxlength="5">
                <a href="javascript:;" class="btn" id="addTag">提交</a>
            </div>
            <h2 class="titles">设置一个能体现随游的价值和好客之道的价格</h2>

            <div class="box01">
                <span>详情描述<b class="form_tip" id="infoTip"></b></span>
                <textarea id="info" placeholder="更近一步的介绍您的随游，讲讲您亲身的体验经历，告知旅行者注意事项"></textarea>

                <!--
                <div class="bjyPro bj5Pro01">
                    <h2 class="tit bgGreen">基本价格</h2>

                    <h2 class="titles">每个随游都与众不同，通过添加描述，让你的随游给游客留下深刻的印象</h2>
                </div>
                -->
            </div>

            <div class="biaoqian clearfix">
                <div class="box02">
                    <span>随游标签<b class="form_tip" id="tagsTip"></b></span>
                    <ul class="clearfix" id="tagsUl">
                        <!--<li class="active-bj">家庭</li>-->
                        <?php foreach ($tagList as $tag) { ?>
                            <li><?= $tag ?></li>
                        <?php } ?>
                        <li class="add"><img src="/assets/images/addG.png" width="25" height="25"></li>
                    </ul>
                    <!--
                    <div class="bjyPro bj5Pro02">
                        <h2 class="tit bgGreen">基本价格</h2>

                        <p>你可以通过设置低廉的价格吸引到头一批游客从而积攒好评，之后可以适当提高价格</p>
                    </div>
                    -->
                </div>
                <div class="box03">
                    <span>随游亮点</span>
                    <ol id="tripSpecialList">
                    </ol>
                    <div class="bjyPro bj5Pro03">
                        <h2 class="tit bgGreen">随游亮点</h2>

                        <p>用文字和照片单独描述您随游中的亮点，让随游中的亮点，让游客眼前一亮吧！</p>
                    </div>
                </div>
                <div class="clearfix">
                    <span><a href="javascript:;" class="addL"><img src="/assets/images/addG.png" width="25" height="25"></a>添加随游亮点</span>

                    <div class="tog clearfix">
                        <form id="special_form">
                            <h2 class="title">添加亮点</h2>
                            <span>亮点名称</span>
                            <input type="text" id="special_name"  maxlength="30">
                            <span>亮点描述</span>
                            <textarea id="special_info" placeholder="最多150个字" maxlength="150"></textarea>
                            <span>上传图片</span>
                            <a href="javascript:;" onclick="NewTrip.showChoseSpecialDiv();" class="fr colGreen selPic">从已上传图片中选取</a>
                            <div id="special_div" class="pic fPic">
                                <p class="special_upload_tip"></p>
                                <img src="/assets/images/addd.png" width="380">
                            </div>
                            <input id="specile_file" type="file"/>

                            <div id="special_queue" class="queue"></div>
                            <input type="hidden" id="special_img"/>

                            <p class="upload_tip">上传文件大小请不能大于2M，支持格式png、jpg、jpeg</p> <br/>
                            <a href="javascript:;" class="btn sbu colGreen" id="addSpecial">提交</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!---step5 end-->

    </div>
    <div class="stepBtn clearfix">
        <a href="javascript:;" class="bjy-prev colOrange" id="bjy-prev">上一步</a>
        <a href="javascript:;" class="bjy-next colGreen" id="bjy-next">下一步</a>
    </div>
</div>


<form id='coordinates_form' method="post">
    <input type='hidden' id="img_x" name='x' class='x' value='0'/>
    <input type='hidden' id="img_y" name='y' class='y' value='0'/>
    <input type='hidden' id="img_w" name='w' class='w' value='0'/>
    <input type='hidden' id="img_h" name='h' class='h' value='0'/>
    <input type='hidden' id="img_rotate" name='rotate' class='rotate' value='0'/>
    <input type="hidden" id="img_src" name="src" value=""/>
</form>

<div id="showTripImgDiv" class="picPop" style="display: none">
    <div class="pic clearfix">
        <p id="show_img_tip">正在上传...</p>
        <img id="img_origin" style="display: none"/>
    </div>
    <a href="###" class="btn sure" id="show_img_confirm">确定</a>
    <a href="javascript:;" class="btn cancle" id="show_img_cancel">取消</a>
</div>


<div class="syBjPro screens" id="choseSpecialDiv" style="display: none;">
    <ul class="clearfix pics">
    </ul>
    <a href="javascript:;" onclick="NewTrip.closeChoseSpecialDiv()" class="btn">取消</a>
</div>


<script type="text/javascript">


    $(document).ready(function () {
        /*添加标签*/
        xcbjy();
        bz('bjybox', 'bjy-bj');
        /*添加标签*/

        $("#bjy-prev").hide();

        initUploadfive();
        NewTrip.initTrip()
        CutImg.initCutImg();

        //初始化上传封面图
    });


    var NewTrip = function () {

        var specialList=[];

        var resetSpecialForm = function () {
            $("#special_name").val("");
            $("#special_info").val("");
            $("#special_img").val("");
            $("#special_div img").attr("src", "/assets/images/addd.png");
        };

        /**
         * 初始化上传随游封面图
         */
        var initUploadTitleImg = function () {
            $("#divCardFront").bind("click", function () {
                $("#titleImgFile").click();
            });
        };

        /**
         * 初始化数字选择器
         */
        var initTouchSpin = function () {
            $("#peopleCount").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000,
                mousewheel:false
            });

            $("#basePrice").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000000,
                mousewheel:false
            });

            $(".step_people").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000,
                mousewheel:false
            });
            $(".step_price").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000000,
                mousewheel:false
            });

            $(".service_price_step").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000000,
                mousewheel:false
            });

            $("#tripLong").TouchSpin({
                buttondown_class: "btn-link",
                buttonup_class: "btn-link",
                max: 10000,
                mousewheel:false
            });
        };

        /**
         * 初始化验证
         */
        var initValidate = function () {

            $("#title").bind("blur", function () {
                if ($("#title").val() == "") {
                    $("#titleTip").html("请输入随游标题");
                } else {
                    $("#titleTip").html("");
                }
                ;
            });
            $("#basePrice").bind("focus", function () {
                $("#basePriceTip").html("");
            });

            $("#peopleCount").bind("focus", function () {
                $("#peopleCountTip").html("");
            });
            $("#beginTime").bind("focus", function () {
                $("#serviceTimeTip").html("");
            });
            $("#tripLong").bind("focus", function () {
                $("#tripLongTip").html("");
            });

            $("#info").bind("focus", function () {
                $("#infoTip").html("");
            });
            $("#tagsUl li").bind("click", function () {
                $("#tagsTip").html("");
            });
        };

        /**
         * /动态初始化SELECT
         */
        var initSelect = function () {
            $(".serviceSelect").each(function () {
                try {
                    $(this).select2({
                        width: '110px',
                        allowClear: true,
                        dropdownCssClass: 'service_price'
                    });
                } catch (e) {
                }
            });
        };

        /**
         * 切换TAB
         * @param count
         */
        var selectTab = function (count) {
            $("#bjy-box ul li").eq(count - 1).click();
        };

        /**
         * 级联获取城市列表
         */
        var getCityList = function () {
            var countryId = $("#countryId").val();
            if (countryId == "") {
                return;
            }
            $("#countryTip").html("");
            $("#cityId").empty();

            $("#cityId").append("<option value=''></option>");
            $("#cityId").val("").trigger("change");
            $.ajax({
                url: '/country/find-city-list',
                type: 'post',
                data: {
                    countryId: countryId,
                    _csrf: $('input[name="_csrf"]').val()

                },
                error: function () {
                    $("#cityTip").html("获取城市列表失败");
                },
                success: function (data) {
                    var datas = eval('(' + data + ')');
                    if (datas.status == 1) {
                        var html = "";
                        for (var i = 0; i < datas.data.length; i++) {
                            var city = datas.data[i];
                            html += '<option value="' + city.id + '">' + city.cname + "/" + city.ename + '</option>';
                        }
                        $("#cityId").append(html);
                    } else {
                        $("#cityTip").html("获取城市列表失败");
                    }
                }
            });
        };

        /**
         *  初始化国家，城市
         */
        var initCountryCity = function () {
            $(".select2").select2({
                'width': '440px',
                containerCss: {
                    'display': 'block',
                    'margin': 'auto',
                    'margin-bottom': '40px'

                },
                formatNoMatches: function () {
                    return "暂无匹配数据";
                }
            });

            //绑定获取城市列表
            $("#countryId").on("change", function () {
                $("#countryTip").html("");
                getCityList();
            });
            $("#cityId").on("change", function () {
                if ($("#cityId").val() != "") {
                    $("#cityTip").html("");
                }
            });
        };

        /**
         *  初始化时间选择器
         */
        var initTimePicker = function () {
            $('.timepicker_start').timepicki({
                format_output: function (tim, mini, meri) {
                    return tim + ":" + mini + " " + meri;
                }
            });
            $('.timepicker_end').timepicki({
                custom_classes: 'time_end',
                format_output: function (tim, mini, meri) {
                    return tim + ":" + mini + " " + meri;
                }
            });
        };

        /**
         *  初始化明细标签
         */
        var initDetailTags = function () {


            $(".detail_tags").bind("click", function () {
                var div;
                if ($(this).attr("type") == "include") {
                    div = $("#include_detail");
                } else {
                    div = $("#uninclude_detail");
                }
                if ($(div).find("input").size() == 1 && $.trim($(div).find("input").eq(0).val()) == "") {
                    $(div).find("input").eq(0).val($(this).html())
                } else {
                    var html = '<p><input type="text" value="' + $(this).html() + '" class="text2"><a href="javascript:;" onclick="NewTrip.removeDetail(this)" class="jian"></a></p>';
                    $(div).append(html);
                }
            });
        };

        /**
         *  初始化随游标签
         */
        var initTripTag = function () {
            $(".box02 ul li").bind("click", function () {
                if ($(this).hasClass("add")) {
                    return true;
                }
                if ($(this).hasClass("active-bj")) {
                    $(this).removeClass("active-bj");
                } else {
                    $(this).addClass("active-bj");
                }
            });
        };


        var initBtnClick = function () {

            $("#uploadPic").bind("click", function () {
                var file = $("#picFile");
                $("#uploadifive-picFile input[type='file']").last().click();
            });

            $("#addScenic").bind("click", function () {
                NewTrip.addScenic();
            });

            $("#addServicePrice").bind("click", function () {
                NewTrip.addServicePrice();
            });

            $("#addStepPrice").bind("click", function () {
                NewTrip.addStepPrice();
            });

            $("#preview").bind("click", function () {
                NewTrip.saveTrip(2);
            });

            $("#tripFinish").bind("click", function () {
                NewTrip.saveTrip(1);
            });

            $("#basePriceType").bind("change", function () {
                if ($(this).val() == TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT) {
                    $("#step_div_content").hide();
                } else {
                    $("#step_div_content").show();
                }
            });

            $("#addSpecial").bind("click",function(){
                NewTrip.addSpecial();
            })

            $("#addTag").bind("click",function(){
                NewTrip.addCusTag();
            });
        };

        /**
         * 获取景区详情
         * @param obj
         */
        var findScenicInfo=function (obj) {
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
        };


        return {

            initTrip: function () {
                initCountryCity();
                initValidate();
                initTouchSpin();
                initDetailTags();
                initSelect();
                initUploadTitleImg();
                initTimePicker();
                initTripTag();
                initBtnClick();
            },
            showChoseSpecialDiv:function(){
                var html='';
                $("#upload_div a[class='imgs'][id!='uploadPic'] img").each(function () {
                    html+='<li><a href="javascript:;" onclick="NewTrip.choseSpecialImg(this)"><img src="'+$(this).attr("src")+'" width="203" height="113"></a></li>'
                });
                if(html==''){
                    Main.showTip("您还有没上传随游照片哦");
                    return;
                }
                $("#choseSpecialDiv ul").html(html);
                $(".mask").show();
                $("#choseSpecialDiv").show();
            },
            choseSpecialImg:function(obj){
                var src=$(obj).find("img").attr("src");
                $("#special_div img").attr("src",src);
                $("#special_img").val(src);
                $("#choseSpecialDiv").hide();
                $(".mask").hide();
            },
            closeChoseSpecialDiv:function(){
                $("#choseSpecialDiv").hide();
                $(".mask").hide();
            },
            /**
             * 添加特殊亮点
             */
            addSpecial:function(){
                var name=$("#special_name").val();
                var info=$("#special_info").val();
                var img=$("#special_img").val();
                if(name==""){
                    Main.showTip("请输入亮点名称");
                    return;
                }
                if(info==""){
                    Main.showTip("请输入详情");
                    return;
                }
                if(img==""){
                    Main.showTip("请上传亮点图片");
                    return;
                }
                var shortName=name;
                var shortInfo=info;
                if(name.length>13){
                    shortName=name.substring(0,13)+"...";
                }
                if(info.length>25){
                    shortInfo=info.substring(0,25)+"...";
                }

                var html='';
                html+='<li>';
                html+='     <a href="javascript:;" class="addPic" onclick="NewTrip.removeSpecial(this)"><img src="/assets/images/minO.png" width="25" height="25"></a>';
                html+='     <a href="javascript:;" class="pic"><img src="'+img+'" width="145" height="81"></a>';
                html+='     <div class="text">';
                html+='         <h3 class="tit special_list_name" data="'+name+'">'+shortName+'</h3>';
                html+='         <p class="special_list_info" data="'+info+'">'+shortInfo+'</p>';
                html+='     </div>';
                html+=' </li>'

                $("#tripSpecialList").append(html);


                resetSpecialForm();
            },

            /**
             * 添加自定义标签
             */
            addCusTag:function(){
                var tag=$("#cusTagTitle").val();
                var html='<li class="active-bj" type="cus" onclick="$(this).remove()">'+tag+'</li>';
                $("#tagsUl").find("li[class='add']").before(html);
                $("#addTagDiv").hide();
                $(".mask").hide();
                $("#cusTagTitle").val("");
            },

            /**
             * 保存随游
             * @param saveType
             */
            saveTrip: function (saveType) {
                var title = $("#title").val();
                var titleImg = $("#titleImg").attr("src");
                var intro = title;

                var countryId = $("#countryId").val();
                var cityId = $("#cityId").val();
                var scenicList = new Array();
                var picList = new Array();

                var basePrice = $("#basePrice").val();
                var basePriceType = $("#basePriceType").val();
                var peopleCount = $("#peopleCount").val();
                var stepPriceList = new Array();
                var serviceList = new Array();
                var includeDetailList = new Array();
                var unIncludeDetailList = new Array();
                var beginTime = $("#beginTime").val();
                var endTime = $("#endTime").val();
                var tripLong = $("#tripLong").val();
                var tripKind = $("#tripKind").val();

                var info = $("#info").val();
                var tagList = new Array();
                var cusTagList=new Array();
                var highlightList = new Array();
                var specialList=new Array();


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
                    var title = $(this).val();
                    var lon = $(this).attr("lon");
                    var lat = $(this).attr("lat");
                    if(lon != "" && lon != undefined && lat != "" && lat != undefined) {
                        var scenic = [title, lon, lat];
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
                var size = $("#upload_div a[class='imgs'][id!='uploadPic'] img").size();
                if (size == 0) {
                    selectTab(3);
                    Main.showTip("请至少上传一张图片");
                    return;
                }
                if ($("#upload_div span[class!='delet']").size() > 0) {
                    selectTab(3);
                    Main.showTip("您有图片正在上传，请上传完成后再进行提交");
                    return;
                }

                //TAB 4验证
                $("#upload_div a[class='imgs'][id!='uploadPic'] img").each(function () {
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
                $("#include_detail input").each(function () {
                    var name = $(this).val();
                    if (name != "") {
                        includeDetailList.push(name);
                    }
                });
                $("#uninclude_detail input").each(function () {
                    var name = $(this).val();
                    if (name != "") {
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
                    error = true;
                }
                $("#tagsUl li").each(function () {
                    if ($(this).hasClass("active-bj")) {
                        if($(this).attr("type")=="cus"){
                            cusTagList.push($(this).html());
                        }else{
                            tagList.push($(this).html());
                        }

                    }
                });

                $("#highlight_div input").each(function () {
                    var value = $(this).val();
                    if (value != "") {
                        highlightList.push(value);
                    }
                });

                $("#tripSpecialList li").each(function(){
                    var special_list_name=$(this).find("h3").attr("data");
                    var special_list_info=$(this).find("p[class='special_list_info']").attr("data");
                    var special_list_img=$(this).find("a[class='pic']").find("img").attr("src");
                    var special=[special_list_name,special_list_info,special_list_img];
                    specialList.push(special);
                });
                if (tagList.length == 0&&cusTagList.length==0) {
                    $("#tagsTip").html("至少要选择一个标签哦~");
                    error = true;
                }

                if (error) {
                    selectTab(5);
                    return;
                }

                $.ajax({
                    url: '/trip/save-trip',
                    type: 'post',
                    data: {
                        title: title,
                        titleImg: titleImg,
                        intro: intro,
                        countryId: countryId,
                        cityId: cityId,
                        scenicList: scenicList,
                        picList: picList,
                        basePrice: basePrice,
                        basePriceType: basePriceType,
                        peopleCount: peopleCount,
                        stepPriceList: stepPriceList,
                        serviceList: serviceList,
                        includeDetailList: includeDetailList,
                        unIncludeDetailList: unIncludeDetailList,
                        beginTime: beginTime,
                        endTime: endTime,
                        tripLong: tripLong,
                        tripKind: tripKind,
                        info: info,
                        tagList: tagList,
                        highlightList: highlightList,
                        specialList:specialList,
                        cusTagList:cusTagList,
                        status: saveType

                    },
                    beforeSend: function () {
                        $("#preview").attr("disabled", "disabled");
                    },
                    error: function () {
                        $("#preview").removeAttr("disabled");
                        Main.showTip("保存随游失败");
                    },
                    success: function (data) {
                        $("#preview").removeAttr("disabled");
                        data = eval("(" + data + ")");
                        if (data.status == 1) {
                            window.location.href = '/view-trip/info?trip=' + data.data.tripId;
                        } else {
                            Main.showTip("保存随游失败");
                        }
                    }
                });
            },

            /**
             * 添加阶梯价格
             */
            addStepPrice: function () {
                var html = '<p><input type="text" value="" class="step_people"><b>人</b><em>&nbsp;至&nbsp;</em>' +
                    '<input type="text" value="" class="step_people"><b>人</b>' +
                    '<input type="text" value="" class="step_price"><b>RMB</b>' +
                    '<a href="javascript:;" onclick="NewTrip.removeStepPrice(this)" class="jian"></a>' +
                    '</p>';
                $("#stepDiv").append(html);
                $(".step_people").TouchSpin({
                    buttondown_class: "btn-link",
                    buttonup_class: "btn-link",
                    max: 10000,
                    mousewheel:false

                });
                $(".step_price").TouchSpin({
                    buttondown_class: "btn-link",
                    buttonup_class: "btn-link",
                    max: 10000000,
                    mousewheel:false
                });
            },

            /**
             * 移除阶梯价格
             * @param obj
             */
            removeStepPrice: function (obj) {
                $(obj).parent("p").remove();
            },

            /**
             * 添加专项服务
             */
            addServicePrice: function () {
                var html = ' <dd style="z-index:11"><input type="text" value="" class="m0-input"><input type="text" value="" class="service_price_step">' +
                    '<div class="sect"><select id="test" class="serviceSelect"><option value="1">一人</option><option value="0">一次</option></select>' +
                    '</div><a href="javascript:;" onclick="NewTrip.removeServicePrice(this)" class="jian"></a>' +
                    '</dd>';
                $("#stepDl").append(html);
                initSelect();
                $(".service_price_step").TouchSpin({
                    buttondown_class: "btn-link",
                    buttonup_class: "btn-link",
                    max: 10000000,
                    mousewheel:false
                });

            },

            /**
             * 移除专项服务
             * @param obj
             */
            removeServicePrice: function (obj) {
                $(obj).parent("dd").remove();
            },

            /**
             * 添加随游明细
             * @param type=true include
             */
            addDetail: function (type) {
                var html = '<p><input type="text" value="" class="text2"><a href="javascript:;" onclick="NewTrip.removeDetail(this)" class="jian"></a></p>';
                if (type) {
                    $("#include_detail").append(html);
                } else {
                    $("#uninclude_detail").append(html);
                }
            },

            /**
             * 删除明细
             * @param obj
             */
            removeDetail: function (obj) {
                $(obj).parent().remove();
            },

            /**
             * 添加亮点
             */
            addHighlight: function () {
                var html = '<p><input type="text" value="" class="text2"><a href="javascript:;" onclick="NewTrip.removeHighlight(this)" class="jian"></a></p>';
                $("#highlight_div").append(html);
            },

            /**
             * 移除亮点
             * @param obj
             */
            removeHighlight: function (obj) {
                $(obj).parent().remove();
            },

            /**
             * 加载地图
             * @param obj
             */
            loadLocation: function (obj) {
                $("#scenicTip").html("");
                var lon = $(obj).attr("lon");
                var lat = $(obj).attr("lat");


                if (lon == '' || lat == '') {
                    findScenicInfo(obj);
                }
            },

            /**
             * 搜索地图
             * @param obj
             */
            searchLocation: function (obj) {
                $("#scenicTip").html("");
                var title = $(obj).attr("title");
                var name = $(obj).val();

                if (title != name) {
                    findScenicInfo(obj);
                }
            },

            /**
             * 移除列表中图片
             * @param obj
             */
            removePic: function (obj) {
                $(obj).parent().remove();
                var size = $("#upload_div a[class='imgs'][id!='uploadPic'] img").size();
                if (size >= 10) {
                    $("#uploadPic").hide();
                }else{
                    $("#uploadPic").show();
                }
            },

            /**
             * 添加景区
             */
            addScenic: function () {
                var html = '<div class="jing"><input type="text" placeholder="景点" onfocus="NewTrip.loadLocation(this)" onblur="NewTrip.searchLocation(this)" /><a href="javascript:;" onclick="NewTrip.removeScenic(this)" class="remove"></a></div>';
                $("#scenicList").append(html);
                //同时删除选中坐标
            },

            /**
             * 删除景区
             * @param obj
             */
            removeScenic: function (obj) {
                $(obj).parent().remove();
            },

            removeSpecial:function(obj){
                $(obj).parent().remove();
            }


        };

    }();

    var CutImg = function () {
        var rotate;
        var rotateCount = 0;
        var containerDivWidth = 520;
        var containerDivHeight = 450;

        var x = 153;
        var y = 100;

        var imgAreaSelectApi;


        return {
            initCutImg: function () {
                //截图弹窗点击确认  选择截取头像
                $("#show_img_confirm").bind("click", function () {
                    CutImg.selectImg();
                });

                //弹窗点击取消
                $("#show_img_cancel").bind("click", function () {
                    $("#showTripImgDiv").hide();
                    $("#myMask").hide();
                    CutImg.removeImgAreaSelect();
                });

                //图片加载完成触发事件
                $('#img_origin').load(function () {
                    var form = $('#coordinates_form');

                    //获取 x、y、w、h的值
                    var left = parseInt(form.children('.x').val());
                    var top = parseInt(form.children('.y').val());
                    var width = parseInt(form.children('.w').val());
                    var height = parseInt(form.children('.h').val());

                    //imgAreaSelectApi 就是图像img_origin的实例 上边instance已解释
                    //setSelection(),设置选区的坐标
                    //update(),更新
                    imgAreaSelectApi.setSelection(left, top, left + width, top + height);
                    imgAreaSelectApi.update();

                    //图片居中
                    var imgWidth = $("#img_origin").width();
                    var imgHeight = $("#img_origin").height();
                    $("#img_origin").css("margin", "0");
                    $("#img_origin").attr("oldWidth", imgWidth);
                    $("#img_origin").attr("oldHeight", imgHeight);

                    if ((containerDivWidth / containerDivHeight) < (imgWidth / imgHeight)) {
                        $("#img_origin").width(containerDivWidth);
                    } else {
                        $("#img_origin").height(containerDivHeight);
                    }
                    imgWidth = $("#img_origin").width();
                    imgHeight = $("#img_origin").height();

                    if (imgWidth > imgHeight) {
                        var padding = (containerDivHeight - imgHeight) / 2;
                        $("#img_origin").css("margin-top", padding);
                        //imgAreaSelectApi.setSelection((imgWidth/2)-(imgHeight/4), (imgHeight/2)-(imgHeight/4), (imgWidth/2)+(imgHeight/4), (imgHeight/2)+(imgHeight/4), true);

                    }
                    if (imgHeight > imgWidth) {
                        var padding = (containerDivWidth - imgWidth) / 2;
                        $("#img_origin").css("margin-left", padding);
                        //imgAreaSelectApi.setSelection((imgHeight/2)-(imgWidth/4)-padding, (imgHeight/2)-(imgWidth/4), (imgHeight/2)+(imgWidth/4)-padding, (imgHeight/2)+(imgWidth/4), true);

                    }
                    if (imgHeight == imgWidth) {
                        if (containerDivHeight > containerDivWidth) {
                            $("#img_origin").css("margin-top", (containerDivHeight - imgHeight) / 2);
                        } else {
                            $("#img_origin").css("margin-left", (containerDivWidth - imgWidth) / 2);
                        }
                    }
                    if (imgWidth > 153 && imgHeight > 100) {
                        imgAreaSelectApi.setSelection(0, 0, 153, 100, true);
                    }
                    imgAreaSelectApi.setOptions({show: true});

                    imgAreaSelectApi.update();
                    CutImg.preview($("#img_origin"), imgAreaSelectApi.getSelection());

                });
            },
            /**
             * 重置剪切图片插件
             */
            resetUploadHeadImg: function () {
                removeImgAreaSelect();
                $("#uploadBtn").val("点击上传图片");
                $("#uploadBtn").show();
                $("#img_origin").hide();
                $("#img_origin").attr("src", "");
                $("#img_src").val();
            },

            /**
             * 重置截头像插件
             */
            resetImg: function () {
                imgAreaSelectApi.update();
            },

            /**
             * 移除截图选择器
             */
            removeImgAreaSelect: function () {
                if (Main.isNotEmpty(imgAreaSelectApi)) {
                    imgAreaSelectApi.cancelSelection();
                }
            },

            /**
             * 初始化头像截取插件
             * @param imgObj
             */
            initImgAreaSelect: function (imgObj) {
                imgAreaSelectApi = $(imgObj).imgAreaSelect({
                    instance: true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
                    onSelectChange: this.preview,	// 改变选区时的回调函数
                    handles: true,	// true，调整手柄则会显示在选择区域内
                    fadeSpeed: 200,
                    resizable: true,
                    aspectRatio: "153:100"
                });
                $(".mask").unbind("click");
            },

            /**
             * 上传完成预览事件
             * @param img
             * @param selection
             */
            preview: function (img, selection) {
                var form = $('#coordinates_form');
                //重新设置x、y、w、h的值
                form.children('.x').val(selection.x1);
                form.children('.y').val(selection.y1);
                form.children('.w').val(selection.x2 - selection.x1);
                form.children('.h').val(selection.y2 - selection.y1);
            },

            /**
             * 上传头像选择IMG（截头像）
             */
            selectImg: function () {
                var x = $("#img_x").val();
                var y = $("#img_y").val();
                var w = $("#img_w").val();
                var h = $("#img_h").val();
                var imgSrc = $("#img_src").val();

                if (imgSrc == "") {
                    Main.showTip("您还没有选择图片哦！");
                    return;
                }
                if (w == 0 || h == 0) {
                    Main.showTip("请正确选择图片！");
                    return;
                }
                if (isNaN(w) || isNaN(h)) {
                    Main.showTip("请正确选择图片！");
                    return;
                }


                $(".p_chose_title_img").html("正在上传，请稍后。。。");
                $(".p_chose_title_img").show();
                $("#titleImg").hide();
                $("#titleImg").attr("src", "");
                $("#show_img_cancel").click();


                $.ajax({
                    url: "/upload/cut-trip-img",
                    type: "post",
                    data: {
                        "x": x,
                        "y": y,
                        "w": w,
                        "h": h,
                        "src": imgSrc,
                        "pWidth": $("#img_origin").width(),
                        "pHeight": $("#img_origin").height()
                    },
                    error: function () {
                        Main.showTip("上传随游图片异常，请刷新重试！");
                    },
                    success: function (data) {
                        var result = eval("(" + data + ")");
                        if (result.status == 1) {
                            $(".p_chose_title_img").html("");
                            $(".p_chose_title_img").hide();
                            $("#titleImg").show();
                            $("#titleImg").attr("src", result.data);

                            return;
                        } else {
                            $(".p_chose_title_img").val("上传失败，请稍后重试。。。");
                        }
                    }
                });
            }

        };
    }();


    /**
     * 初始化封面图上传插件
     */
    function initUploadfive() {

        $('#specile_file').uploadifive({
            'auto': true,
            'queueID': 'special_queue',
            'uploadScript': '/upload/upload-trip-title-img',
            'multi': false,
            'onAddQueueItem': function (file) {
                $("#special_div img").hide();
                $(".special_upload_tip").show();
                $(".special_upload_tip").html("正在上传，请稍后...");
            },
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#special_img").val(datas.data);
                    $("#special_div img").show();
                    $(".special_upload_tip").hide();
                    $("#special_div img").attr("src",datas.data);
                } else {
                    $(".special_upload_tip").html("上传失败，请稍后重试...");
                }
            }
        });
        $("#special_div").bind("click", function () {
            $("#uploadifive-specile_file input[type='file'][id!='specile_file']").last().click();
        });
        $('#picFile').uploadifive({
            'auto': true,
            'queueID': 'frontQueue',
            'uploadScript': '/upload/upload-trip-title-img',
            'multi': false,
            'dnd': false,
            'onAddQueueItem': function (file) {
                var html = '<a href="javascript:;" class="imgs" pic="' + file.name + file.size + '"><span class="upload_show_info">正在上传...</span><span class="delet" onclick="NewTrip.removePic(this)"></span><img /></a>';
                $("#upload_div").prepend(html);
            },
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                var pic = file.name + file.size;
                var a = $("#upload_div").find("a[pic='" + pic + "']");
                if (datas.status == 1) {
                    $(a).find("img").attr("src", datas.data);
                    $(a).find("span").eq(0).hide();
                    $(a).find("span[class='upload_show_info']").remove();
                    var size = $("#upload_div a[class='imgs'][id!='uploadPic'] img").size();
                    if (size >= 10) {
                        $("#uploadPic").hide();
                    }else{
                        $("#uploadPic").show();
                    }

                } else {
                    $(a).find("span").eq(0).html("上传失败");
                    $(a).remove();

                }
            }
        });

        //封面图
        $("#titleImgFile").bind("change", function () {
            var file = this.files[0];
            //判断类型是不是图片
            if (!/image\/\w+/.test(file.type)) {
                Main.showTip("请确保文件为图像类型");
                return false;
            }
            if (file.type.indexOf("gif") != -1) {
                Main.showTip("请确保文件为图像类型为JPG、PNG");
                return false;
            }
            //判断大小
            if (file.size > 2048000) {
                Main.showTip("图片大小不能超过2M");
                return false;
            }
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                //$("#upload_div").append('<img src="'+this.result+'" width="205" height="115">');
                if ($("#showTripImgDiv").css("display") == "none") {
                    $("#myMask").show();
                    $("#showTripImgDiv").show();
                }
                $("#img_origin").width("");
                $("#img_origin").height("");
                $("#img_origin").attr("src", this.result);
                $("#img_src").val(this.result);
                $("#img_origin").show();
                $("#show_img_tip").hide();
                $("#uploadBtn").hide();
                $("#titleImgTip").html("");
                CutImg.initImgAreaSelect("#img_origin");
            }
        });
    }


</script>