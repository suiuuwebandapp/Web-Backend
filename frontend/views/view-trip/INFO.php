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

<div class="sydetail w1200 clearfix">
    <div class="titTop clearfix">
        <h3 class="title"><?=$travelInfo['info']['title'];?></h3>
        <p class="xing">
            <img src="<?= $travelInfo['info']['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
        </p>
        <?php $isOwner=$this->context->userPublisherObj!=null&&$this->context->userPublisherObj->userPublisherId==$travelInfo['info']['createPublisherId']?true:false; ?>
        <?php if($isOwner){?>
            <a id="backEdit" href="/trip/edit-trip?trip=<?=$travelInfo['info']['tripId']?>" class="bjBtn">返回编辑</a>
        <?php } ?>
    </div>
    <div class="web-content">
        <div class="web-left">
            <?php if(empty($attention)||$attention==false){?>
            <a href="javascript:;" class="collection" attentionIdTrip="0" id="collection_trip"></a>
            <?php  }else{?>
                <a href="javascript:;" class="collection active" attentionIdTrip="<?php echo $attention['attentionId']?>" id="collection_trip"></a>
            <?php  }?>
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

            <?php if($isOwner){?>
            <div class="newsLists clearfix" id="publisherList">
                <h2 class="title">随游处理</h2>
                <?php foreach($travelInfo['publisherList'] as $publisherInfo){?>
                    <div class="lists clearfix" id="div_trip_publisher_<?=$publisherInfo['tripPublisherId']?>">
                        <img src="<?= $publisherInfo['headImg']?>" alt="" class="userpic">
                        <ul class="clearfix">
                            <li class="li01"><?=$publisherInfo['nickname'];?><img src="/assets/images/xf.fw.png" width="18" height="12">
                                <br>性别:<b><?php if($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b>
                            </li>
                            <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($publisherInfo['birthday']);?></b></li>
                            <li>职业:<b><?=$publisherInfo['profession']?></b></li>
                            <li>随游次数:<b><?=$publisherInfo['travelCount']?></b></li>
                        </ul>
                        <a href="javascript:;" tripPublisherId="<?=$publisherInfo['tripPublisherId']?>" class="sureBtn">移除</a>
                    </div>
                <?php } ?>

            </div>
            <?php } ?>
            <div  id="buyTrip"></div>
            <form action="/user-order/add-order" method="post" id="orderForm">
                <input type="hidden" name="tripId" value="<?=$travelInfo['info']['tripId'];?>" id="tripId"/>
                <input type="hidden" name="serviceIds" id="serviceIds" />
            <div class="route">
                <h2 class="title">购买路线</h2>
                <ul>
                    <li><span>出发日期：</span><p><input type="text" class="text" name="beginDate" id="beginTime"><a href="javascript:;" class="cal-icon" id="calendar"></a></p></li>
                    <li><span>出游人数: </span><p><input type="text"  class="text" name="peopleCount" id="peopleCount"></p></li>
                    <li><span>起始时间：</span><p><input type="text"  class="text" name="startTime" id="startTime"></p></li>
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
                        <a href="javascript:;" id="addOrder" class="btn" <?=$isOwner?'disabled style="background-color: #ddd"':''?> >支付</a>
                    </p>
                </div>
            </div>
            </form>
            <div class="web-con">
                <div id="pllist" class="web-bar">
                    <ol>
                        <li><a href="#pinglun">评论</a></li>
                        <li></li>
                        <li id="fenxiang"><a href="###">分享</a>
                            <div id="other-line" class="bdsharebuttonbox" data-tag="share_1">
                                <a href="#" class="icon sina" data-cmd="tsina"></a>
                                <a href="#" class="icon wei" data-cmd="weixin"></a>
                                <a href="#" class="icon qq" data-cmd="qzone"></a>

                            </div>
                        </li>
                    </ol>

                </div>
                <div class="zhuanlan-web">
                    <ul id="tanchu_pl">
                    </ul>
                    <ol id="spage">
                    </ol>
                </div>
                <div class="zhuanlan-text">

                    <textarea id="pinglun"></textarea>
                    <a href="javascript:;" class="zl-btn" onclick="submitComment()">发表评论</a>
                </div>
            </div>

        </div>
        <div class="web-right">
            <div class="user">
                <div class="user-name">
                    <img src="<?=$createUserInfo->headImg;?>" alt="" class="user-pic">
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
            <input id="toBuy" type="button" value="购买路线" class="web-btn5" <?=$isOwner?'disabled style="background-color: #ddd"':''?> >
            <input id="toApply" type="button" value="申请加入路线" class="web-btn6" <?=$isOwner?'disabled style="background-color: #ddd"':''?> >
            <div class="web-tuijian">
                <h4>日本京都奈良公园一日游</h4>
                <img src="/assets/images/23.png" alt="" class="pic">
                <p class="xing">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                </p>
                <div>奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽</div>
                <span>总价:<a>1234345</a></span>
            </div>
            <div class="web-tuijian">
                <h4>日本京都奈良公园一日游</h4>
                <img src="/assets/images/23.png" alt="" class="pic">
                <p class="xing">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                </p>
                <div>奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽</div>
                <span>总价:<a>1234345</a></span>
            </div><div class="web-tuijian">
                <h4>日本京都奈良公园一日游</h4>
                <img src="/assets/images/23.png" alt="" class="pic">
                <p class="xing">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start1.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                    <img src="/assets/images/start2.fw.png" width="13" height="13">
                </p>
                <div>奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽奈良公园位于街的东边，东西长4公里、南北宽</div>
                <span>总价:<a>1234345</a></span>
            </div>

        </div>
    </div>
</div>


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
    var userPublisherId='<?= $this->context->userPublisherObj!=null?$this->context->userPublisherObj->userPublisherId:''?>';
    var userId='<?= $this->context->userObj!=null?$this->context->userObj->userSign:''?>';
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
        initDatePicker();
        $("#collection_trip").bind("click",function(){
            submitCollection();
        });
        getComment(1);
        $("#peopleCount").bind("blur",function(){
            showPrice();
        });
        $("#serviceLi input[type='checkbox']").bind("click",function(){
            showPrice();
        });
        $("#calendar").bind("click",function(){
            $("#beginTime").focus();
        });
        $("#toApply").bind("click",function(){
            if(userPublisherId==''){
                Main.showTip("登录后并且成为随友才能加入线路！");
                return;
            }
            window.location.href='/trip/to-apply-trip?trip='+$("#tripId").val();
        });

        $("#toBuy").bind("click",function(){
            if(userId==''){
                Main.showTip("登录后才能购买哦~！");
                return;
            }
            $("html,body").animate({scrollTop: $("#buyTrip").offset().top-30}, 500);
        });

        $("#addOrder").bind("click",function(){
            var peopleCount=$("#peopleCount").val();
            var tripId=$("#tripId").val();
            var beginDate=$("#beginTime").val();
            var startTime=$("#startTime").val();
            var serviceArr=[];
            var serviceIds='';


            if(userId==''){
                Main.showTip("登录后才能购买哦~！");
                return;
            }
            if(beginDate==''){
                Main.showTip("请选择您的出行日期");
                return;
            }
            if(peopleCount==''||peopleCount==0){
                Main.showTip("请输入你的出行人数");
                return;
            }
            peopleCount=parseInt(peopleCount);
            maxPeopleCount=parseInt(maxPeopleCount);

            if(peopleCount>maxPeopleCount){
                Main.showTip("这个随友最多之能接待"+maxPeopleCount+"个小伙伴呦~");
                return;
            }
            if(startTime==''){
                Main.showTip("请输入您的起始时间");
                return;
            }

            $("#serviceLi input[type='checkbox']").each(function(){
                if($(this).is(":checked")){
                    serviceArr.push($(this).attr("serviceId"));
                }
            });
            if(serviceArr!=''&&serviceArr.length>0){
                serviceIds=serviceArr.join(",");
            }

            $("#serviceIds").val(serviceIds);
            $("#orderForm").submit();

        });

        //绑定移除
        $("#publisherList div a").bind("click",function(){
            var tripPublisherId=$(this).attr("tripPublisherId");
            var tripId=$("tripId").val()
            $.ajax({
                url :'/trip/remove-publisher',
                type:'post',
                data:{
                    tripId:tripId,
                    tripPublisherId:tripPublisherId,
                    _csrf: $('input[name="_csrf"]').val()
                },
                error:function(){
                    //hide load
                    Main.showTip("移除随友失败");
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        $("#div_trip_publisher_"+tripPublisherId).remove();
                    }else{
                        Main.showTip("移除随友失败");
                    }
                }
            });

        });
    });


    /**
     * 初始化日期控件
     */
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

    function submitCollection()
    {
        var tripId=$("#tripId").val();
        if(tripId==''||tripId==undefined||tripId==0)
        {
            Main.showTip('未知的随游');
            return;
        }
       var isCollection=false;
        if($('#collection_trip').attr('class')=='collection')
        {
            $('#collection_trip').attr('class','collection active');
            isCollection = true;
        }else
        {
            $('#collection_trip').attr('class','collection');
            isCollection=false;
        }

        if(isCollection)
        {
            //添加收藏
            $.ajax({
                url :'/view-trip/add-collection-travel',
                type:'post',
                data:{
                    travelId:tripId,
                    _csrf: $('input[name="_csrf"]').val()
                },
                error:function(){
                    //hide load
                    Main.showTip("收藏随游失败");
                    $('#collection_trip').attr('class','collection');
                    isCollection=false;
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        $('#collection_trip').attr('attentionIdTrip',data.data);
                    }else{
                        Main.showTip(data.data);
                        $('#collection_trip').attr('class','collection');
                        isCollection=false;
                    }
                }
            });
        }else
        {
            //取消收藏
            $.ajax({
                url :'/view-trip/delete-attention',
                type:'post',
                data:{
                    attentionId:$('#collection_trip').attr('attentionIdTrip'),
                    _csrf: $('input[name="_csrf"]').val()
                },
                error:function(){
                    //hide load
                    $('#collection_trip').attr('class','collection active');
                    isCollection = true;
                    Main.showTip("收藏随游失败");
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){

                    }else{
                        $('#collection_trip').attr('class','collection active');
                        isCollection = true;
                        Main.showTip(data.data);
                    }
                }
            });
        }
    }
</script>
<script>
    var rid=0;
    var tripId=$("#tripId").val();
    var page=1;
    var rSign='';
    function getComment(page)
    {
        $.ajax({
            type: 'post',
            url: '/view-trip/get-comment-list',
            data: {
                tripId: tripId,
                cPage:page,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {
                    $('#tanchu_pl').html('');
                    var str='';
                    for(var i=0;i<obj.data.length;i++)
                    {
                        var r=obj.data[i].rTitle;
                        if(r==null)
                        {
                            r='';
                        }
                        var c='';
                        var status=obj.data[i].status;
                        if(status==1)
                        {
                            c='active'
                        }
                        str+='<li>';
                        str+='<div class="user-pic fl">';
                        str+='<img src=\"'+obj.data[i].headImg+'\" alt=\"\">';
                        str+='<span class=\"user-name\">';
                        str+=obj.data[i].nickname;
                        str+="</span></div><p class='fl'><b>";
                        str+=r;
                        str+="</b>";
                        str+=' '+obj.data[i].content;
                        str+="</p><div class='fr resp'><a href='javascript:;' onclick='sumbmitZan("+obj.data[i].commentId+","+"this)' class='picon zan "+c+"'></a><a href='#pllist' rSign='"+obj.data[i].userSign+"' id='"+obj.data[i].commentId+"' class='picon huifu' onclick='reply(this)'></a>";
                        str+="</div></li>";
                    }
                    $('#tanchu_pl').append(str);

                    $('#spage').html('');
                    $('#spage').append(obj.message);

                    $("#spage li a").click(function() {
                        var page=$(this).attr('page');
                        getComment(page);
                    });

                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    }
    function reply(obj)
    {
        rid=$(obj).attr('id');
        rSign=$(obj).attr('rSign');
        var t=$(obj).parent("div").prev().prev().find("span").html();
        $("#pinglun").val('@'+t+'   :');

    }

    function submitComment()
    {
        var s=$('#pinglun').val();
        var i =s.indexOf(':');
        if(i==-1){
            var content= s;
            var t='';
        }else
        {

            var t=s.slice(0,i);
            var content= s.slice(i);
        }

        $.ajax({
            type: 'post',
            url: '/view-trip/add-comment',
            data: {
                tripId: tripId,
                content: content,
                rTitle: t,
                rId: rid,
                rSign:rSign,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {
                    //Main.showTip("发表成功。。。");
                    getComment(page);
                    $("#pinglun").val('');
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    }

    function sumbmitZan(id,obj)
    {
        var s =$(obj).attr('class');
        var i =s.indexOf('active');
        if(i!=-1){
            Main.showTip('已经点赞');
            return;
        }
        $(obj).attr('class','picon zan active');
        $.ajax({
            type: 'post',
            url: '/view-trip/add-support',
            data: {
                tripId: tripId,
                rId: id,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {

                    //Main.showTip("发表成功。。。");
                    getComment(page);
                }else
                {
                    Main.showTip(obj.data);
                    $(obj).attr('class','picon zan');

                }
            }
        });
    }


    window._bd_share_config = {
        common : {
            bdText : '随游网-<?=$travelInfo['info']['intro']?>',
            bdDesc : '随游网-<?=htmlentities($travelInfo['info']['title'])?>',
            bdUrl : '<?=Yii::$app->params['base_dir'].'/view-trip?trip='.$travelInfo['info']['tripId'];?>',
            bdPic : '<?=$travelInfo['info']['titleImg']?>'
        },
        share : [{
            "bdSize" : 16
        }]
    }

    //以下为js加载部分
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>