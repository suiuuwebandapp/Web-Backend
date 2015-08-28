<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/8/25
 * Time : 下午4:57
 * Email: zhangxinmailvip@foxmail.com
 */
?>
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<link rel="stylesheet" type="text/css" href="/assets/pages/trip/new-traffic-trip.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/datetimepicker/DateTimePicker.css" />

<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="/assets/plugins/datetimepicker/DateTimePicker-ltie9.css" />
<script type="text/javascript" src="/assets/plugins/datetimepicker/DateTimePicker-ltie9.js"></script>
<![endif]-->

<script type="text/javascript" src="/assets/plugins/datetimepicker/DateTimePicker.js"></script>
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/xcbjy.js"></script>
<input type="hidden" id="tripId" value="<?=$travelInfo['info']['tripId']?>" />
<div class="jtbjy clearfix" id="bjy-box">
    <ul id="bz">
        <li class="active"><a href="javascript:;">上传封面</a></li>
        <li><a href="javascript:;">基本信息</a></li>
        <li><a href="javascript:;">车辆信息</a></li>
        <li><a href="javascript:;">设置价格</a></li>
        <li><a href="javascript:;">服务详情</a></li>
    </ul>
    <div class="bjy-list clearfix">
        <!--step1-->
        <div class="bjy-bj1 bjy-bj" style=" display:block;">
            <span class="spn_title">随游名称</span>
            <span class="form_tip" id="titleTip"></span>
            <input id="title" class="name" type="text" placeholder="清晰且具有描述性" value="<?=$travelInfo['info']['title']?>" />

            <?=frontend\widgets\TripTitleImg::widget(['defaultImg'=>$travelInfo['info']['titleImg']]); ?>
            <!--step1 提示-->

            <div class="bjyPro bj1Pro01">
                <h2 class="tit bgGreen">随游名称</h2>
                <p>给随游起一个好名字是让游客 认识您的随游的重要途径</p>
                <p class="p2">示列：法国美食文化半日慢行</p>

            </div>
            <div class="bjyPro bj1Pro02">
                <h2 class="tit bgGreen">封面图</h2>
                <p>封面图会显示在搜索列表页面及推荐页面，好的封面图会为您的随游引来更多关注。</p>
            </div>
        </div>
        <!--step1 End-->

        <!--step2 begin-->
        <div class="bjy-bj2 bjy-bj">
            <span class="spn_country_city">您主要可以服务的地区</span>
            <span class="form_tip" id="countryTip"></span>
            <div class="lines">
                <select id="countryId" name="country" class="select2" placeholder="国家" required style="margin-right: 5px">
                    <option value=""></option>
                    <?php foreach ($countryList as $c) { ?>
                        <option value="<?= $c['id'] ?>"
                            <?php  if($c['id']==$travelInfo['info']['countryId']){echo "selected";} ?>>
                            <?= $c['cname'] . "/" . $c['ename'] ?>
                        </option>
                    <?php } ?>
                </select>
                <select id="cityId" name="city" class="select2" placeholder="城市" required></select>
            </div>
            <span class="spn_license">驾照发放时间</span>
            <span class="form_tip" id="licenseTip"></span>
            <div class="lines">
                <div class="selets cty">
                    <select name="" id="licenseYear" >
                        <?php for($i=0;$i<=30;$i++){$y=date("Y",time())-$i;?>
                            <option <?=$y==date("Y",strtotime($travelInfo['trafficInfo']['driverLicenseDate']))?'selected':'';?> value='<?=$y?>'><?=$y.'年'?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="selets">
                    <select name="" id="licenseMonth">
                        <?php for($i=1;$i<=12;$i++){?>
                            <option <?=$i==date("m",strtotime($travelInfo['trafficInfo']['driverLicenseDate']))?'selected':'';?> value='<?=$i?>'><?=$i.'月'?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <span class="spn_maxUserCount">可接待人数</span>
            <span class="form_tip" id="maxUserCountTip"></span>
            <div class="lines">
                <div class="selets cty">
                    <input type="text" ipt-type="number_ipt" class="line_ipt" id="maxUserCount" value="<?=$travelInfo['info']['maxUserCount']?>">
                    <span class="line_span">人</span>
                </div>
            </div>
            <span class="spn_title">接受预定时间</span>
            <span class="form_tip" id="scheduledTimeTip"></span>
            <div class="time">
                <input type="radio" name="scheduledRadio" value="1" id="yes"><label for="yes" class="lab1">随时接受预定</label>
                <input type="radio" name="scheduledRadio" value="2" id="no"><label for="no">需提前<input id="scheduledTime" ipt-type="number_ipt" value="<?=empty($travelInfo['info']['scheduledTime'])?'':$travelInfo['info']['scheduledTime']/(60*60*24);?>" type="text" placeholder="几天">预定</label>
            </div>
            <script type="text/javascript">
                $(function(){
                    $('.jtbjy .bjy-bj2 .time input:radio').click(function(e) {

                        if($(this).attr("id")=="no"&&$(this).prop("checked")){
                            $('.jtbjy .bjy-bj2 .time label input').css('display','inline-block');
                        }else{
                            $('.jtbjy .bjy-bj2 .time label input').css('display','none')
                        }
                    });
                })
            </script>
        </div>
        <!--step2 end-->

        <!--step3 begin-->
        <div class="bjy-bj3 bjy-bj">
            <h2 class="titles">填写关于您车辆的基本信息</h2>
            <span class="spn_title">车型</span>
            <span class="form_tip" id="carTypeTip" style="margin-right: 14px"></span>
            <input type="text" class="car" id="carType" value="<?=$travelInfo['trafficInfo']['carType']?>">
            <!--step3Pro-->
            <div class="bjyPro bj3Pro01">
                <h2 class="tit bgGreen">车型</h2>
                <p>车辆信息对游客十分重要，请您如实填写车辆品牌+车型</p>
                <p class="p2">示例：别克GL8</p>
            </div>

            <div class="lines clearfix">
                <div class="line_two_ipt_div">
                    <span class="spn_title" style="width: auto">座位数</span> <span class="form_tip" id="seatCountTip" style="width: 145px !important;"></span>
                    <div class="selets cty">
                        <input type="text" class="line_ipt" id="seatCount" ipt-type="number_ipt" value="<?=$travelInfo['trafficInfo']['seatCount']?>">
                        <span class="line_span">座</span>
                    </div>
                </div>
                <div class="line_two_ipt_div">
                    <span class="spn_title pack" style="width: auto">行李空间</span> <span class="form_tip" id="spaceTip" style="width: 130px !important;"></span>
                    <div class="selets pack">
                        <input type="text" id="space" value="<?=$travelInfo['trafficInfo']['spaceInfo']?>">
                    </div>
                </div>
                <!--step3Pro-->
                <div class="bjyPro bj3Pro02">
                    <h2 class="tit bgGreen">行李空间</h2>
                    <p>简短清晰的描述行李空间的大小，比如最多可以摆放多少件行李</p>
                </div>
            </div>
            <ul class="list clearfix">
                <li>
                    <span>乘客吸烟</span>
                    <input type="radio" name="allowSmokeRadio" id="a1" value="1"><label for="a1" class="label01">允许</label>
                    <input type="radio" name="allowSmokeRadio" id="a2" value="0"><label for="a2">不允许</label>
                </li>
                <li>
                    <span>携带宠物</span>
                    <input type="radio" name="allowPetRadio" id="b1" value="1"><label for="b1" class="label01">允许</label>
                    <input type="radio" name="allowPetRadio" id="b2" value="0"><label for="b2">不允许</label>
                </li>
                <li>
                    <span>儿童座椅</span>
                    <input type="radio" name="childseatRadio" id="c1" value="1"><label for="c1" class="label01">有</label>
                    <input type="radio" name="childseatRadio" id="c2" value="0"><label for="c2">没有</label>
                </li>

            </ul>
            <h2 class="titles">添加车辆照片</h2>
            <div class="carPic clearfix">
                <p class="line_p_tip" id="carPhotoTip"></p>
                <div id="upload_div">
                    <?php
                    if($travelInfo['picList']!=null){
                        foreach($travelInfo['picList'] as $pic){
                            ?>
                            <a href="javascript:;" class="imgs"><span class="delet" onclick="NewTrafficTrip.removePic(this)"></span><img src="<?=$pic['url']?>"></a>
                        <?php
                        }
                    }
                    ?>
                    <a id="uploadPic" class="imgs"><img src="/assets/images/addPic.gif"></a>
                </div>
                <div>
                    <input type="file" id="picFile" style="display: none"/>
                    <div id="frontQueue" class="queue"></div>
                    <input type="hidden" id="tripTitleImg"/>
                </div>
                <div class="bjyPro bj3Pro03">
                    <h2 class="tit bgGreen">车辆照片</h2>
                    <p>车俩照片是游客选择时最直观的依据，随游要求您上传至少5张照片<br><br></p>
                    <p>
                        建议包括：<br>
                        车辆外部最新照片<br>
                        车辆内室最新照片<br>
                        您与车辆的合照<br>
                    </p>
                </div>

            </div>
        </div>
        <!------step3  end-->

        <!----step4 ------>
        <div class="bjy-bj4 bjy-bj">
            <h2 class="titles">包车服务价格</h2>
            <div class="line">
                <input type="radio" id="carServiceRadio"><label for="carServiceRadio">我不提供全天包车服务</label>
            </div>
            <div class="price1 clearfix">
                <div id="carServiceDiv">
                    <span class="spn_title">基本价格</span>
                    <span class="form_tip" id="carBasePriceTip" style="margin-right: 17px"></span>
                    <p><input type="text" class="text01" id="carBasePrice" ipt-type="number_ipt" value="<?=empty($travelInfo['trafficInfo']['carPrice'])?'':$travelInfo['trafficInfo']['carPrice'];?>"><b>RMB/每天</b></p>
                    <div class="lines clearfix">
                        <div class="line_two_ipt_div">
                            <span class="spn_title" style="width: auto">服务时长</span> <span class="form_tip" id="serviceTimeTip" style="width: 130px !important;"></span>
                            <div class="selets cty">
                                <input type="text" class="line_ipt" id="serviceTime" ipt-type="number_ipt" value="<?=empty($travelInfo['trafficInfo']['serviceTime'])?'':$travelInfo['trafficInfo']['serviceTime'];?>">
                                <span>小时/每天</span>
                            </div>
                        </div>
                        <div class="line_two_ipt_div">
                            <span class="spn_title" style="width: auto">服务里程</span> <span class="form_tip" id="serviceMileageTip" style="width: 130px !important;"></span>
                            <div class="selets cty">
                                <input type="text" class="line_ipt" id="serviceMileage" ipt-type="number_ipt"  value="<?=empty($travelInfo['trafficInfo']['serviceMileage'])?'':$travelInfo['trafficInfo']['serviceMileage'];?>">
                                <span>公里/每天</span>
                            </div>
                        </div>
                    </div>
                    <div class="lines clearfix">
                        <div class="line_two_ipt_div">
                            <span class="spn_title" style="width: auto">超时费用</span> <span class="form_tip" id="overTimeTip" style="width: 130px !important;"></span>
                            <div class="selets cty">
                                <input type="text" class="line_ipt" id="overTime" ipt-type="number_ipt"  value="<?=empty($travelInfo['trafficInfo']['overTimePrice'])?'':$travelInfo['trafficInfo']['overTimePrice'];?>">
                                <span>￥/小时</span>
                            </div>
                        </div>
                        <div class="line_two_ipt_div">
                            <span class="spn_title" style="width: auto">超程费用</span> <span class="form_tip" id="overMileageTip" style="width: 130px !important;"></span>
                            <div class="selets cty">
                                <input type="text" class="line_ipt" id="overMileage" ipt-type="number_ipt"  value="<?=empty($travelInfo['trafficInfo']['overMileagePrice'])?'':$travelInfo['trafficInfo']['overMileagePrice'];?>">
                                <span>￥/公里</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bjyPro bj4Pro01">
                    <h2 class="tit bgGreen">服务价格</h2>
                    <p>
                        建议您的价格是： <br>
                        根据您所在地区的旅游需求定价<br>
                        参考您所在城市的消费水平<br>
                        根据您服务里的涵盖内容<br>

                    </p>
                    <p class="p2">价格是影响游客预订服务的重要因素，具有性价比的价格会更受游客欢迎</p>
                </div>

                <h2 class="titles mT">接送机服务价格</h2>
                <div class="line">
                    <input type="radio" id="airplaneServiceRadio"><label for="airplaneServiceRadio">我不提供接送机服务</label>
                </div>
                <div id="airplaneServiceDiv">
                    <span class="spn_title">基本价格</span>
                    <span class="form_tip" id="airBasePriceTip" style="margin-right: 17px"></span>
                    <p><input type="text" class="text02" id="airBasePrice" ipt-type="number_ipt" value="<?=empty($travelInfo['trafficInfo']['airplanePrice'])?'':$travelInfo['trafficInfo']['airplanePrice'];?>"><b>每次</b></p>
                    <h2 class="titles mT">夜间加价</h2>
                    <div class="line">
                        <input type="radio" id="f" name="nightPriceRadio" value="0"><label for="f">不加收夜间费用</label>
                        <input type="radio" id="g" name="nightPriceRadio" value="1"><label for="g" style="display: inline-block">加收夜间服务费</label>
                        <span class="form_tip" id="nightPriceTip" style="margin-right: 17px"></span>
                    </div>

                    <div class="serverPrice clearfix" style="display: none" id="nextPriceDiv">
                        <p>
                            <input type="text" class="timePicker" data-field="time" id="nightTimeBegin" placeholder="起始时间" value="<?=empty($travelInfo['trafficInfo']['nightTimeStart'])?'':\common\components\DateUtils::formatTime($travelInfo['trafficInfo']['nightTimeStart']);?>" />
                            <span>-</span>
                            <input type="text" class="timePicker" data-field="time" id="nightTimeEnd" placeholder="结束时间" value="<?=empty($travelInfo['trafficInfo']['nightTimeEnd'])?'':\common\components\DateUtils::formatTime($travelInfo['trafficInfo']['nightTimeEnd']);?>"/>
                            <span>加收夜间服务费</span>
                        </p>
                        <p><span>夜间加收服务费</span><input type="text" class="text" ipt-type="number_ipt" id="nightTimePrice" value="<?=empty($travelInfo['trafficInfo']['nightServicePrice'])?'':$travelInfo['trafficInfo']['nightServicePrice'];?>"><b>￥/每次</b></p>
                    </div>
                </div>
            </div>

        </div>

        <!---step4--end-->


        <!---step5- begin-->
        <div class="bjy-bj5 bjy-bj clearfix"  id="bjy">
            <div class="box01">
                <span class="spn_title">服务描述</span>
                <span class="form_tip" id="infoTip"></span>
                <textarea id="info" placeholder="告诉游客关于您车辆信息之外有哪些服务优势"><?=$travelInfo['info']['info'];?></textarea>
                <div class="bjyPro bj5Pro01">
                    <h2 class="tit bgGreen">服务描述</h2>
                    <p>游客不仅想了解服务的车辆信息，更希望看到服务优势的介绍。</p>
                    <p>
                        建议包含以下内容： <br>
                        可解决语言不通、交通不便、出行费用高昂等各类问题<br>
                        服务提供者独特的个人身份及背景，如：资深司机兼导游<br>
                        车辆整洁舒适、熟悉当地路况、旅途更安全更放心<br>
                    </p>
                </div>
            </div>

            <h2 class="titles mT">可提供服务时间</h2>
            <div class="line">
                <input type="radio" id="h" name="serviceTimeRadio" value="1" ><label for="h">24小时服务</label>
                <input type="radio" id="i" name="serviceTimeRadio" value="0"><label for="i" style="display: inline-block">固定时间可接受服务</label>
                <span class="form_tip" id="canServiceTimeTip" style="width: 200px !important;"></span>
            </div>
            <div class="serverPrice clearfix" id="serviceTimeDiv" style="display: none">
                <p>
                    <input type="text" class="timePicker" data-field="time" id="serviceTimeBegin" placeholder="起始时间" value="<?=empty($travelInfo['info']['startTime'])?'':\common\components\DateUtils::formatTime($travelInfo['info']['startTime']);?>" />
                    <span>-</span>
                    <input type="text" class="timePicker" data-field="time" id="serviceTimeEnd" placeholder="结束时间" value="<?=empty($travelInfo['info']['endTime'])?'':\common\components\DateUtils::formatTime($travelInfo['info']['endTime']);?>"/>
                    <span>可接受服务</span>
                </p>
            </div>
            <h2 class="titles mT">服务包含的内容</h2>
            <div class="sevBox clearfix">
                <input type="checkbox" id="check1"><label for="check1">燃油费</label>
                <input type="checkbox" id="check2"><label for="check2">过路费</label>
                <input type="checkbox" id="check3"><label for="check3">过桥费</label>
                <input type="checkbox" id="check4"><label for="check4">停车费</label>
                <input type="checkbox" id="check5"><label for="check5">服务小费</label>
                <input type="checkbox" id="check6"><label for="check6">司机餐饮费</label>
                <input type="checkbox" id="check7"><label for="check7">协助办理入住</label>
                <input type="checkbox" id="check8"><label for="check8">行李搬运</label>
                <input type="checkbox" id="check9"><label for="check9">办理退税</label>
                <input type="checkbox" id="check10"><label for="check10">导游讲解</label>
            </div>

            <div class="adds clearfix">
                <span class="title">其他服务中包括的内容（选填）</span>
                <div id="include_detail">
                    <p><input type="text"><a href="javascript:;" onclick="NewTrafficTrip.addDetail(true)" class="add"></a></p>
                </div>
                <span class="title">其他服务中不包括的内容（选填）</span>
                <div id="uninclude_detail">
                    <p><input type="text"><a href="javascript:;" onclick="NewTrafficTrip.addDetail(false)" class="add"></a></p>
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
<div id="nightTimeBox"></div>


<script type="text/javascript">

    var tripId='<?=$travelInfo['info']['tripId'];?>';
    var allowSmoke='<?=$travelInfo['trafficInfo']['allowSmoke'];?>';
    var allowPet='<?=$travelInfo['trafficInfo']['allowPet'];?>';
    var childSeat='<?=$travelInfo['trafficInfo']['childSeat'];?>';
    var cityId='<?=$travelInfo['info']['cityId']; ?>';
    <?php
        $inDetail=[];
        $unDetail=[];
        foreach($travelInfo['includeDetailList'] as $detail){
            $inDetail[]=$detail['name'];
        }
        foreach($travelInfo['unIncludeDetailList'] as $detail){
            $unDetail[]=$detail['name'];
        }
    ?>
    var inDetail='<?=empty($inDetail)?'':implode(",",$inDetail)?>';
    var unInDetail='<?=empty($unDetail)?'':implode(",",$unDetail)?>';

    $(document).ready(function(){
        xcbjy();
        bz('bjybox','bjy-bj','NewTrafficTrip');
        NewTrafficTrip.initTrip();

    });


    var NewTrafficTrip = function(){

        /**
         * 级联获取城市列表
         */
        var getCityList = function () {
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
                        if(cityId!=""){
                            $("#cityId").val(cityId).trigger("change");
                        }
                    }else{
                        $("#cityTip").html("获取城市列表失败");
                    }
                }
            });
        };

        /**
         *  初始化国家，城市
         */
        var initCountryCity=function(){
            $(".select2").select2({
                'width': '215px',
                containerCss: {
                    'display': 'inline-block'

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
                    $("#countryTip").html("");
                }
            });
            getCityList();
        };

        var initDateTimePicker=function(){
            $("#nightTimeBox").DateTimePicker({
                defaultDate:new Date(2000,0,00),
                titleContentTime:'选择时间',
                setButtonContent:'确定',
                clearButtonContent:'取消',
                timeFormat: 'HH:mm'
            });
        };


        var initCarUploadImg=function(){
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
            $("#uploadPic").bind("click", function () {
                $("#carPhotoTip").html("");
                var file = $("#picFile");
                $("#uploadifive-picFile input[type='file']").last().click();
            });
        };

        var initDefaultRadio=function(){
            if($("#scheduledTime").val()!=''){
                checkRadio($("input:radio[name='scheduledRadio'][value=2]"),true);
                $("#scheduledTime").show();
            }else{
                checkRadio($("input:radio[name='scheduledRadio'][value=1]"),true);
            }
            if(allowSmoke==1){
                checkRadio($("input:radio[name='allowSmokeRadio'][value=1]"),true);
            }else{
                checkRadio($("input:radio[name='allowSmokeRadio'][value=0]"),true);
            }
            if(allowPet==1){
                checkRadio($("input:radio[name='allowPetRadio'][value=1]"),true);
            }else{
                checkRadio($("input:radio[name='allowPetRadio'][value=0]"),true);
            }
            if(childSeat==1){
                checkRadio($("input:radio[name='childseatRadio'][value=1]"),true);
            }else{
                checkRadio($("input:radio[name='childseatRadio'][value=0]"),true);
            }

            if($("#carBasePrice").val()==''){
                checkRadio($("#carServiceRadio"),true);
                $("#carServiceDiv").hide();
            }else{
                checkRadio($("#carServiceRadio"),false);
                $("#carServiceDiv").show();
            }
            if($("#airBasePrice").val()==''){
                checkRadio($("#airplaneServiceRadio"),true);
                $("#airplaneServiceDiv").hide();
            }else{
                checkRadio($("#airplaneServiceRadio"),false);
                $("#airplaneServiceDiv").show();
            }

            if($("#nightTimePrice").val()!=''){
                checkRadio($("input:radio[name='nightPriceRadio'][value=1]"),true);
                $("#nextPriceDiv").show();
            }else{
                checkRadio($("input:radio[name='nightPriceRadio'][value=0]"),true);
                $("#nextPriceDiv").hide();
            }
            if($("#serviceTimeBegin").val()!=''){
                checkRadio($("input:radio[name='serviceTimeRadio'][value=0]"),true);
                $("#serviceTimeDiv").show();
            }else{
                checkRadio($("input:radio[name='serviceTimeRadio'][value=1]"),true);
                $("#serviceTimeDiv").hide();
            }

        };

        var initNumberInput=function(){

            Main.setInputOnlyNumber($("input[ipt-type='number_ipt']"));

        };

        var checkRadio=function(obj,type){
            if(type){
                $(obj).prop("checked",true);
                $(obj).next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px');
            }else{
                $(obj).prop("checked",false);
                $(obj).next('label').css('background-position','0 10px').siblings('label').css('background-position','0 -47px');
            }

        };

        var initRadioClick=function(){
            $("input:radio[name='nightPriceRadio']").change(function(){
                if($(this).val()==1){
                    $("#nextPriceDiv").show();
                }else{
                    $("#nextPriceDiv").hide();
                }
            });

            $("input:radio[name='serviceTimeRadio']").change(function(){
                if($(this).val()==0){
                    $("#serviceTimeDiv").show();
                }else{
                    $("#serviceTimeDiv").hide();
                }
            });

            $("#carServiceRadio").click(function(){
               if($(this).attr("checked")!="checked"){
                   $(this).attr("checked","checked");
                   checkRadio(this,true);
                   $("#carServiceDiv").hide();
                   if($("#airplaneServiceRadio").attr("checked")=="checked"){
                       $("#airplaneServiceRadio").removeAttr("checked");
                       checkRadio($("#airplaneServiceRadio"),false);
                       $("#airplaneServiceDiv").show();
                   }
               }else{
                   $(this).removeAttr("checked");
                   checkRadio(this,false);
                   $("#carServiceDiv").show();
               }
            });

            $("#airplaneServiceRadio").click(function(){
                if($(this).attr("checked")!="checked"){
                    $(this).attr("checked","checked");
                    checkRadio(this,true);
                    $("#airplaneServiceDiv").hide();
                    if($("#carServiceRadio").attr("checked")=="checked"){
                        $("#carServiceRadio").removeAttr("checked");
                        checkRadio($("#carServiceRadio"),false);
                        $("#carServiceDiv").show();
                    }
                }else{
                    $(this).removeAttr("checked");
                    checkRadio(this,false);
                    $("#airplaneServiceDiv").show();
                }
            });

        };

        var initValidate=function(){
            $("#title").bind("focus",function(){
                $("#titleTip").html("");
            });
            $("#maxUserCount").bind("focus",function(){
                $("#maxUserCountTip").html("");
            });
            $("input:radio[name='scheduledRadio']").change(function(){
                $("#scheduledTimeTip").html("");
            });
            $("#scheduledTime").bind("focus",function(){
                $("#scheduledTimeTip").html("");
            });

            $("#carType").bind("focus",function(){
                $("#carTypeTip").html("");
            })
            $("#seatCount").bind("focus",function(){
                $("#seatCountTip").html("");
            });
            $("#space").bind("focus",function(){
                $("#spaceTip").html("");
            });


            $("#carBasePrice").bind("focus",function(){
                $("#carBasePriceTip").html("");
            });
            $("#serviceTime").bind("focus",function(){
                $("#serviceTimeTip").html("");
            });
            $("#serviceMileage").bind("focus",function(){
                $("#serviceMileageTip").html("");
            });
            $("#overTime").bind("focus",function(){
                $("#overTimeTip").html("");
            });
            $("#overMileage").bind("focus",function(){
                $("#overMileageTip").html("");
            });
            $("#airBasePrice").bind("focus",function(){
                $("#airBasePriceTip").html("");
            });
            $("#nightTimeBegin,#nightTimeEnd,#nightTimePrice").bind("click",function(){
                $("#nightPriceTip").html("");
            });
            $("input:radio[name='nightPriceRadio']").change(function(){
                $("#nightPriceTip").html("");
            });
            $("#serviceTimeBegin,#serviceTimeEnd").bind("focus",function(){
                $("#canServiceTimeTip").html("");
            });
            $("input:radio[name='serviceTimeRadio']").change(function(){
                $("#canServiceTimeTip").html("");
            });

            $("#carServiceRadio").change(function(){
                $("#carBasePriceTip").html("");
            });
            $("#airplaneServiceRadio").change(function(){
                $("#airBasePriceTip").html("");
            });
            $("#info").bind("focus",function(){
                $("#infoTip").html("");
            });
        };
        var checkCheckbox=function(obj,type){
            if(type){
                $(obj).prop("checked",true);
                $(obj).next('label').css('background-position','0 -157px');
            }else{
                $(obj).prop("checked",false);
                $(obj).next('label').css('background-position','0 -102px');
            }
        };
        /**
         * 切换TAB
         * @param count
         */
        var selectTab = function (count) {
            $("#bjy-box ul li").eq(count - 1).click();
        };

        var initDetail=function(){
            var cusIn=new Array()
            var cusUnIn=new Array();
            var defaultChose=[];
            cusIn=inDetail.split(",");
            cusUnIn=unInDetail.split(",");
            $(".sevBox input:checkbox").each(function() {
                var temp = $(this).next().html();
                defaultChose.push(temp);
                if ($.inArray(temp, cusIn) != -1) {
                    checkCheckbox($(this), true);
                }
            });
            var unFirst=true;
            var inFirst=true;

            for(var key in cusIn){
                if($.inArray(cusIn[key],defaultChose)==-1){
                    if(inFirst){
                        inFirst=false;
                        $("#include_detail input").val(cusIn[key]);
                    }else{
                        NewTrafficTrip.addDetail(1,cusIn[key]);
                    }
                }
            }
            for(var key in cusUnIn){
                if($.inArray(cusUnIn[key],defaultChose)==-1){
                    if(unFirst){
                        unFirst=false;
                        $("#uninclude_detail input").val(cusUnIn[key]);
                    }else{
                        NewTrafficTrip.addDetail(0,cusUnIn[key]);
                    }
                }
            }
        };

        return {

            initTrip: function () {
                initCountryCity();
                initDefaultRadio();
                initRadioClick();
                initDateTimePicker();
                initCarUploadImg();
                initValidate();
                initNumberInput();
                initDetail();
            },
            /**
             * 保存随游
             * @param saveType
             */
            saveTrip: function (saveType) {
                var tripId = $("#tripId").val();
                var title = $("#title").val();
                var titleImg = $("#titleImg").attr("src");
                var countryId = $("#countryId").val();
                var cityId = $("#cityId").val();
                var licenseYear=$("#licenseYear").val();
                var licenseMonth=$("#licenseMonth").val();
                var maxUserCount = $("#maxUserCount").val();
                var scheduledType=$("input:radio[name='scheduledRadio']:checked").val();
                var scheduledTime=$("#scheduledTime").val();

                var carType=$("#carType").val();
                var seatCount=$("#seatCount").val();
                var space=$("#space").val();
                var allowSmoke=$("input:radio[name='allowSmokeRadio']:checked").val();
                var allowPet=$("input:radio[name='allowPetRadio']:checked").val();
                var childSeat=$("input:radio[name='childseatRadio']:checked").val();
                var picList = new Array();

                var carServiceType=false;
                if($("#carServiceRadio").attr("checked")=="checked"){
                    carServiceType=true;
                }
                var carBasePrice=$("#carBasePrice").val();
                var serviceTime=$("#serviceTime").val();
                var serviceMileage=$("#serviceMileage").val();
                var overTime=$("#overTime").val();
                var overMileage=$("#overMileage").val();

                var airServiceType=false;
                if($("#airplaneServiceRadio").attr("checked")=="checked"){
                    airServiceType=true;
                }
                var airBasePrice=$("#airBasePrice").val();

                var nightPriceType=$("input:radio[name='nightPriceRadio']:checked").val();
                var nightTimeBegin=$("#nightTimeBegin").val();
                var nightTimeEnd=$("#nightTimeEnd").val();
                var nightTimePrice=$("#nightTimePrice").val();

                var info = $("#info").val();
                var serviceTimeType=$("input:radio[name='serviceTimeRadio']:checked").val();
                var serviceTimeBegin=$("#serviceTimeBegin").val();
                var serviceTimeEnd=$("#serviceTimeEnd").val();
                var includeDetailList = new Array();
                var unIncludeDetailList = new Array();


                var error = false;


                //TAB 1验证
                if (title == "") {
                    $("#titleTip").html("请输入随游标题");
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
                if (maxUserCount == "") {
                    $("#maxUserCountTip").html("请输入可接待人数");
                    error = true;
                }
                if(scheduledType==2&&scheduledTime==""){
                    $("#scheduledTimeTip").html("请输入接受预定时间");
                    error = true;
                }

                if (error) {
                    selectTab(2);
                    return;
                }


                //TAB 3验证
                if (carType == "") {
                    $("#carTypeTip").html("请输入车型");
                    error = true;
                }
                if (seatCount == "") {
                    $("#seatCountTip").html("请输入座位数");
                    error = true;
                }
                if (space == "") {
                    $("#spaceTip").html("请输入行李空间");
                    error = true;
                }
                if (error) {
                    selectTab(3);
                    return;
                }

                var size = $("#upload_div a[class='imgs'][id!='uploadPic'] img").size();
                if (size <1) {
                    selectTab(3);
                    $("#carPhotoTip").html("请至少上传5张车辆照片");
                    return;
                }
                if ($("#upload_div span[class!='delet']").size() > 0) {
                    selectTab(3);
                    $("#carPhotoTip").html("您有图片正在上传，请上传完成后再进行提交");
                    return;
                }
                $("#upload_div a[class='imgs'][id!='uploadPic'] img").each(function () {
                    picList.push($(this).attr("src"));
                });

                //TAB 4验证

                if(!carServiceType){
                    if (carBasePrice == "") {
                        $("#carBasePriceTip").html("请输入包车服务基础价格");
                        error = true;
                    }
                    if (serviceTime == "") {
                        $("#serviceTimeTip").html("请输入服务时长");
                        error = true;
                    }
                    if (serviceMileage == "") {
                        $("#serviceMileageTip").html("请输入服务里程");
                        error = true;
                    }
                    if (overTime == "") {
                        $("#overTimeTip").html("请输入超时费用");
                        error = true;
                    }
                    if (overMileage == "") {
                        $("#overMileageTip").html("请输入超程费用");
                        error = true;
                    }


                }
                if(!airServiceType){
                    if (airBasePrice == "") {
                        $("#airBasePriceTip").html("请输入接机服务基础价格");
                        error = true;
                    }

                    if(nightPriceType==1){
                        if(nightTimeBegin==''||nightTimeEnd==''||nightTimePrice==''){
                            $("#nightPriceTip").html("请输入夜间服务时间和价格");
                            error = true;
                        }
                    }

                }

                if (error) {
                    selectTab(4);
                    return;
                }
                //TAB 5 验证
                if (info == "") {
                    $("#infoTip").html("请输入详情介绍");
                    error = true;
                }
                if(serviceTimeType==0){
                    if(serviceTimeBegin==""||serviceTimeEnd==""){
                        $("#canServiceTimeTip").html("请输入可接受服务时间");
                        error=true;
                    }
                }
                if (error) {
                    selectTab(5);
                    return;
                }
                $(".sevBox input:checkbox").each(function(){
                    var temp=$(this).next().html();
                    if($(this).is(':checked')){
                        includeDetailList.push(temp);
                    }else{
                        unIncludeDetailList.push(temp);
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

                var license=licenseYear+":"+licenseMonth+":01";
                $.ajax({
                    url: '/trip/update-traffic-trip',
                    type: 'post',
                    data: {
                        tripId:tripId,
                        title: title,
                        titleImg: titleImg,
                        countryId: countryId,
                        cityId: cityId,
                        license: license,
                        picList: picList,
                        maxUserCount: maxUserCount,
                        scheduledType: scheduledType,
                        scheduledTime: scheduledTime,
                        carType: carType,
                        seatCount: seatCount,
                        space: space,
                        allowSmoke: allowSmoke,
                        allowPet: allowPet,
                        childSeat: childSeat,
                        carServiceType: carServiceType,
                        carBasePrice: carBasePrice,
                        serviceTime: serviceTime,
                        serviceMileage: serviceMileage,
                        overTime: overTime,
                        overMileage:overMileage,
                        airServiceType:airServiceType,
                        nightPriceType:nightPriceType,
                        airBasePrice:airBasePrice,
                        nightTimeBegin:nightTimeBegin,
                        nightTimeEnd:nightTimeEnd,
                        nightTimePrice:nightTimePrice,
                        info:info,
                        serviceTimeType:serviceTimeType,
                        serviceTimeBegin:serviceTimeBegin,
                        serviceTimeEnd:serviceTimeEnd,
                        includeDetailList:includeDetailList,
                        unIncludeDetailList:unIncludeDetailList,
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
             * 添加随游明细
             * @param type=true include
             */
            addDetail: function (type,value) {
                if(value==undefined){
                    value="";
                }
                var html = '<p><input type="text" value="'+value+'" class="text2"><a href="javascript:;" onclick="NewTrafficTrip.removeDetail(this)" class="jian"></a></p>';
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
            }
        }
    }();



</script>