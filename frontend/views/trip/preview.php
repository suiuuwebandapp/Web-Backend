<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午6:00
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<link rel="stylesheet" type="text/css" href="/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/time-picki/css/timepicki.css">
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" ></script>

<script type="text/javascript" src="/assets/plugins/time-picki/js/timepicki.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>

<style type="text/css">

    .trip_info{
        font-size: 18px;
        line-height: 35px
    }

    .timepicki-input{
        width: 45px !important;
        padding-left: 0px !important;
    }
    .timepicker_wrap{
        top: 40px !important;
        border-radius:0 !important;
    }
    .prev, .next{
        border-radius: 0 !important;
    }
    .timepicker_start, .timepicker_end{
        width: 177px !important;
    }


    .datetimepicker .prev{
        background-image: url('/assets/images/day_left.png');
        width:20px;
        height: 20px;
        background-repeat: no-repeat;
        background-position: center;
        padding:0;
    }
    .datetimepicker .next{
        background-image: url('/assets/images/day_right.png');
        width:20px;
        height: 20px;
        background-repeat: no-repeat;
        background-position: center;
        padding:0;
    }
    .datetimepicker th ,td{
        padding: 3px 5px;
    }
    .datetimepicker table{
        margin-top: 10px;
    }
    .btn-link,.input-group-btn{
        display: none !important;
    }
</style>

<!---------------预览页--------->
<div class="sydetail w1200 clearfix">
    <input type="hidden" value="<?=$travelInfo['info']['tripId'];?>" id="tripId"/>
    <div class="titTop clearfix">
        <h3 class="title"><?=$travelInfo['info']['title'];?></h3>
        <p class="xing">
            <img src="<?= $travelInfo['info']['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
        </p>
        <a href="javascript:;" class="bjBtn" id="finishTrip">确认发布</a>
        <a id="backEdit" href="/trip/edit-trip?trip=<?=$travelInfo['info']['tripId']?>" class="backbj">返回编辑</a>
    </div>
    <div class="web-content">
        <div class="web-left">
            <a href="###" class="collection"></a>
            <div class="web-banner" id="ban">
                <ul id="banner">
                    <?php foreach($travelInfo['picList'] as $pic ){?>
                        <li><img src="<?= $pic['url'];?>" alt=""></li>
                    <?php }?>
                </ul>
                <ol id="btn">
                    <?php foreach($travelInfo['picList'] as $pic ){?>
                        <li><a href="javascript:;"><img src="<?= $pic['url'];?>" alt=""></a></li>
                    <?php }?>
                    <?php foreach($travelInfo['picList'] as $pic ){?>
                        <li><a href="javascript:;"><img src="<?= $pic['url'];?>" alt=""></a></li>
                    <?php }?>
                </ol>
                <a href="javascript:;" class="pre" id="pre"><img src="/assets/images/prev.png" alt=""></a>
                <a href="javascript:;" class="nex" id="nex"><img src="/assets/images/next.png" alt=""></a>
            </div>
            <div class="map">
                <h2 class="title"><?=$travelInfo['info']['title'];?></h2>
                <ul class="details">
                    <li>
                        <span class="icon icon1">随游
                            <b> <?=\common\components\DateUtils::convertTimePicker($travelInfo['info']['startTime'],2);?> -
                                <?=\common\components\DateUtils::convertTimePicker($travelInfo['info']['endTime'],2);?>
                            </b>
                        </span>
                    </li>
                    <li><span class="icon icon2">随游时长<b id="tripTime"><?=$travelInfo['info']['travelTime'];?></b><?=$travelInfo['info']['travelTimeType']==\common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_DAY?'天':'小时';?></span></li>
                    <li class="last"><span class="icon icon3">随友最多接待<b id="maxPeopleCount"><?=$travelInfo['info']['maxUserCount'];?></b>人</span></li>
                </ul>
                <div class="map-pic">
                    <iframe id="mapFrame" name="mapFrame" src="/google-map/view-scenic-map?tripId=<?=$travelInfo['info']['tripId'];?>" width="893px" height="330px;" frameborder="0" scrolling="no"></iframe>
                </div>
                <div class="trip_info">
                    <?=str_replace("\n","</br>",$travelInfo['info']['info']);?>
                </div>
            </div>
            <div class="route">
                <h2 class="title">购买路线</h2>
                <ul>
                    <li><span>出发日期：</span><p><input type="text" class="text" id="beginTime"><a href="javascript:;" class="cal-icon" id="calendar"></a></p></li>
                    <li><span>出游人数: </span><p><input type="text"  class="text" id="peopleCount"></p></li>
                    <li><span>起始时间：</span><p><input type="text"  class="text" id="startTime"></p></li>
                    <?php foreach($travelInfo['serviceList'] as $key=> $service){  ?>
                        <li id="serviceLi">
                            <span><?=$key==0?'附加服务：':''?></span>
                            <p><input type="checkbox"  class="radio" id="radio<?=$service['serviceId']?>"
                                      serviceId="<?=$service['serviceId']?>" servicePrice="<?=$service['money']?>" serviceType="<?=$service['type']?>"
                                    >
                                <label for="radio<?=$service['serviceId']?>" ><?=$service['title']?></label>
                                <span><b><?=$service['money']?>￥</b></span>
                                <span><?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?'人':'次' ?></span>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
                <div class="pay">
                    <p>
                        <span>总价：<b id="allPrice"><?=$travelInfo['info']['basePrice'];?></span>
                        <a href="javascript:;" class="btn" disabled style="background-color: #ddd">支付</a>
                    </p>
                </div>
            </div>

        </div>
        <div class="web-right">
            <div class="user">
                <div class="user-name">
                    <a href="<?=\common\components\SiteUrl::getViewUserUrl($createUserInfo->userSign)?>" target="_blank"><img src="<?=$createUserInfo->headImg;?>" alt="" class="user-pic"></a>
                    <span><?=$createUserInfo->nickname;?></span>
                </div>
                <p><?=$createUserInfo->intro;?></p>
            </div>
            <div class="pf">
                <ul>
                    <li class="fen">
                        <p class="xing">
                            <img src="<?= $createPublisherInfo->score>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                            <img src="<?= $createPublisherInfo->score>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                            <img src="<?= $createPublisherInfo->score>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                            <img src="<?= $createPublisherInfo->score>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                            <img src="<?= $createPublisherInfo->score>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                        </p>
                    </li>
                    <li>行程数:<b><?=$createPublisherInfo->tripCount;?></b></li>
                    <li>随游次数:<b><?=$createUserInfo->travelCount;?></b></li>
                </ul>
            </div>
            <?php if($travelInfo['serviceList']!=null){ ?>
                <p>附加服务</p>
                <ul class="ul01" id="stepPriceList">
                    <li class="tit"><span>服务</span><span>价格</span><span>单位</span></li>
                    <?php foreach($travelInfo['serviceList'] as $service){  ?>
                        <li><span><?=$service['title']?></span><span><b>¥<?=$service['money']?></b></span><span><?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?'人':'次' ?></span></li>
                    <?php } ?>
                </ul>
            <?php } ?>

            <?php if($travelInfo['priceList']!=null){ ?>
                <p>优惠价格:</p>
                <ul class="ul02">
                    <?php foreach($travelInfo['priceList'] as $price){  ?>
                        <li><span><?=$price['minCount']?>人</span><span>至</span><span><?=$price['maxCount']?>人</span><span><b>¥<?=$price['price']?></b></span></li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <p>基础价格:<b id="basePrice"><?=$travelInfo['info']['basePrice'];?></b>人/次</p>
            <input type="button" value="购买路线" class="web-btn5" disabled style="background-color: #ddd">
            <input type="button" value="申请加入路线" class="web-btn6" disabled style="background-color: #ddd">
        </div>
    </div>
</div>
<!---------预览页-end-------------->

<script type="text/javascript">

    <?php
        $stepPriceJson='';
        if(!empty($travelInfo['priceList'])){
            $stepPriceJson=json_encode($travelInfo['priceList']);
        }
    ?>
    var basePrice='<?=$travelInfo['info']['basePrice'];?>';
    var maxPeopleCount='<?=$travelInfo['info']['maxUserCount'];?>';
    var stepPriceJson='<?=$stepPriceJson;?>';
    var serviceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT;?>';
    var serviceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE;?>';

    $(document).ready(function(){
        $('#startTime').timepicki({
            format_output: function(tim, mini, meri) {
                return tim + ":" + mini + " " + meri;
            }
        });

        $("#peopleCount").TouchSpin({
            buttondown_class: "btn-link",
            buttonup_class: "btn-link",
            max:10000
        });
        $("#finishTrip").bind("click",function(){
            finishTrip();
        });

        initDatePicker();

        $("#peopleCount").bind("blur",function(){
           showPrice();
        });
        $("#serviceLi input[type='checkbox']").bind("click",function(){
            showPrice();
        });
        $("#calendar").bind("click",function(){
            $("#beginTime").focus();
        });

    });


    function finishTrip(){
        var tripId=$("#tripId").val();
        $.ajax({
            url :'/trip/finish-trip',
            type:'post',
            data:{
                tripId:tripId
            },
            beforeSend:function(){
                $("#finishTrip").attr("disabled","disabled");
            },
            error:function(){
                $("#finishTrip").removeAttr("disabled");
                Main.showTip("发布随游失败");
            },
            success:function(data){
                $("#finishTrip").removeAttr("disabled");
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href='/view-trip/info?trip='+tripId;
                }else{
                    Main.showTip("发布随游失败");
                }
            }
        });
    }

    function initDatePicker(){
        $('#beginTime').datetimepicker({
            language:  'zh-CN',
            autoclose:1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format:'yyyy-mm-dd',
            weekStart: 1,
            startDate:'<?=date('Y-m-d',time()); ?>'
        });
        $(".datetimepicker").hide();


        $('#beginTime').unbind("focus");

        $("#beginTime").bind("focus",function(){
            var top=$("#beginTime").offset().top;
            var left=$("#beginTime").offset().left;
            $(".datetimepicker").css({
                'top':top+40,
                'left':left,
                'position':'absolute',
                'background-color':'white',
                'border':'1px solid gray',
                'font-size':'14px'
            });
            $(".datetimepicker").show();
        });

        $(".table-condensed tbody").bind("click",function(){
            $(".datetimepicker").hide();
        });
    }
    //显示价格
    function showPrice()
    {
        var peopleCount=$("#peopleCount").val();

        var tripTime=$("#tripTime").val();
        var allPrice=0;

        if(peopleCount==''||peopleCount==0){
          return;
        }

        peopleCount=parseInt(peopleCount);
        maxPeopleCount=parseInt(maxPeopleCount);

        if(peopleCount>maxPeopleCount){
            Main.showTip("这个随友最多之能接待"+maxPeopleCount+"个小伙伴呦~");
            return;
        }
        //判断有没有阶梯价格
        var stepPriceList=eval("("+stepPriceJson+")");
        if(stepPriceList.length>0){
            for(var i=0;i<stepPriceList.length;i++){
                var stepPrice=stepPriceList[i];
                if(peopleCount>=stepPrice['minCount']&&peopleCount<=stepPrice['maxCount']){
                    allPrice=parseInt(stepPrice['price'])*peopleCount;
                    break;
                }
            }
        }else{
            allPrice=basePrice*peopleCount;
        }
        //判断有没有附加服务
        $("#serviceLi input[type='checkbox']").each(function(){
            var tempPrice=parseInt($(this).attr("servicePrice"));
            if($(this).is(":checked")){
                //如果TRUE每次 FALSE 每人
                if($(this).attr("serviceType")==serviceTypeCount){
                    allPrice+=tempPrice;
                }else{
                    allPrice+=(tempPrice*peopleCount);
                }
            }
        });

        $("#allPrice").html(allPrice);

    }




</script>