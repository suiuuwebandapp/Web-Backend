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

    .bdsharebuttonbox .icon{
        padding: 0;float: none;margin: 0;
    }
    .sydetailBanner .pre{
        border:none;
    }
    .sydetailBanner .next{
        border:none;
    }
    .sydetailBanner .next:hover{
        background-color:transparent;
    }
</style>

<?php $isOwner=$this->context->userPublisherObj!=null&&$this->context->userPublisherObj->userPublisherId==$travelInfo['info']['createPublisherId']?true:false; ?>
<input type="hidden" id="tripId" value="<?=$travelInfo['info']['tripId'];?>" />
<div class="sydetailBanner web-banner">
    <div class="banner">
        <ul class="clearfix">
            <?php foreach($travelInfo['picList'] as $pic ){?>
                <li><a href="javascript:;"><img src="<?= $pic['url'];?>" width="100%" alt=""></a></li>
            <?php }?>
        </ul>
    </div>
    <a href="javascript:;" class="pre"></a>
    <a href="javascript:;" class="next"></a>
</div>

<div class="bgGreen sydetailNav clearfix">
    <div class="w1200 clearfix">
        <ul class="clearfix">
            <li><a href="#imgs">照片</a></li>
            <li><a href="#detail">详情描述</a></li>
            <li><a href="#price">价格内容</a></li>
            <li><a href="#pinglun">评论</a></li>
        </ul>
    </div>
</div>

<div class="sydetail w1200 clearfix">
    <div class="titTop clearfix fl">
        <h3 class="title"><?=$travelInfo['info']['title'];?></h3>
        <p><img src="/assets/images/position.png" width="14" height="18">&nbsp;<?=$travelInfo['info']['countryCname']?>，<?=$travelInfo['info']['cityCname']?></p>
        <p class="xing">
            <img src="/assets/images/biaoqian.png" width="16" height="16">
            <?php foreach(explode(",",$travelInfo['info']['tags']) as $tag){ ?>
                <span><?=$tag;?></span>
            <?php } ?>
            <img src="<?= $travelInfo['info']['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
            <img src="<?= $travelInfo['info']['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
        </p>
    </div>
    <div class="web-content fl">
        <div class="web-left">
            <div class="map">
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

                <p class="title02" id="detail">详情描述</p>
                <div class="trip_info">
                    <?=str_replace("\n","</br>",$travelInfo['info']['info']);?>
                </div>
                <?php if(!empty($travelInfo['specialList'])){ ?>
                    <?php foreach($travelInfo['specialList'] as $special){ ?>
                        <div><img src="<?=$special['picUrl']?>" width="830" height="460"></div>
                        <p class="title"><?=$special['title']?></p>
                        <p><?=$special['info']?></p>
                    <?php } ?>
                <?php } ?>
                <div class="bgGreen idea clearfix">
                    <a href="#" class="cir fl"><img src="<?=$createUserInfo->headImg;?>" width="66" height="66"></a>
                    <div class="fl">
                        <p>推荐理由：</p>
                        <p>依然保留了初建时的许多历史遗迹，如威斯敏斯特厅</p>
                    </div>
                </div>
                <?php if(!empty($travelInfo['includeDetailList'])&&!empty($travelInfo['unIncludeDetailList'])){ ?>
                    <p class="title02" id="price">价格内容</p>
                    <div class="contian clearfix">
                        <?php foreach($travelInfo['includeDetailList'] as $detail){ ?>
                            <span><b class="icon icon01"></b><?=$detail['name']?></span>
                        <?php } ?>
                        <?php foreach($travelInfo['unIncludeDetailList'] as $detail){ ?>
                            <span><b class="icon icon02"></b><?=$detail['name']?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
                <ul class="detNav tabTitle clearfix">
                    <li><a href="javascript:;" class="icon icon01 active">预定流程</a></li>
                    <li><a href="javascript:;" class="icon icon02">退款说明</a></li>
                    <li><a href="javascript:;" class="icon icon03">保险保障</a></li>
                </ul>
                <div class="detCon con01 tabCon" style="display:block;">
                    <div class="line"></div>
                    <p><span>1</span> 咨询随游的发布者，确认游玩细节。</p>
                    <p><span>2</span> 填写日期，人数等信息并预支付订单。</p>
                    <p><span>3</span> 等待随友接单后，通过邮件，短信及站内信方式收到订单提醒。</p>
                    <p><span>4</span> 凭电子确认单进行游玩。</p>
                    <p><span>5</span> 完成游玩后进行确认，评价您选择的随游及服务提供者。</p>
                </div>
                <div class="detCon tabCon">
                    <h3 class="title03">作为用户，您的权益会在随游得到充分保障。</h3>
                    <h3 class="title03">作为旅行者，您如果选择预订随游产品，可以享受以下的退款政策</h3>
                    <p>1.支付并提交订单后48小时无人接单，则订单自动取消，全额返还服务费</p>
                    <p>2.订单提交时间未满48小时，但超过订单预期服务时间的，全额返还服务费</p>
                    <p>3.在订单被接单之前取消订单，全额返还所支付费用</p>
                    <p>4.所提交订单被随友接单，在服务指定日期前5天可以申请取消预订并全额退款</p>
                    <p>5.在指定日期内5天可以申请退款，经平台审核后返还部分预订费用。</p>
                    <p>在随游服务过程中及服务后且未确认完成服务前，可以提交退款请求，经平台调查审核后返还部分服务费用。</p>
                </div>
                <div class="detCon tabCon">
                    <h3 class="title03">全天候客服热线</h3>
                    <p>和随游旅行的过程中，如果有任何问题，随时拨打随游客服电话或在微信公众号上与客服沟通，我们7x24随叫随到，为您服务。</p>
                    <h3 class="title03">旅行保险一份100%赔付</h3>
                    <p>和随游旅行过程中如出现意外情况，随友和游客无需承担保险范围内的任何费用，随游网提供的旅行保险全权处理100%赔付。据统计90%以上的游客和随友的相处都非常愉快，如需赔付，您只需要提供现场相关证据照片，在48小时内与随游客服联系，即可享受保险保障。</p>
                </div>
            </div>

            <?php if($isOwner&&count($travelInfo['publisherList'])>1){?>
                <div class="newsLists clearfix" id="publisherList">
                    <h2 class="title">随友处理</h2>
                    <?php foreach($travelInfo['publisherList'] as $publisherInfo){?>
                        <?php if($publisherInfo['publisherId']==$travelInfo['info']['createPublisherId']){continue;} ?>
                        <?php if(isset($this->context->userPublisherObj)&&$publisherInfo['publisherId']==$this->context->userPublisherObj->userPublisherId){$joinTravel=true;} ?>

                        <div class="lists clearfix" id="div_trip_publisher_<?=$publisherInfo['tripPublisherId']?>">
                            <img src="<?= $publisherInfo['headImg']?>" alt="" class="userpic">
                            <ul class="clearfix">
                                <li class="li01"><?=$publisherInfo['nickname'];?><img src="/assets/images/xf.fw.png" width="18" height="12" style="cursor: pointer" onclick="Main.showSendMessage('<?=$publisherInfo['userSign']?>')">
                                    <br>性别:<b><?php if($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b>
                                </li>
                                <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($publisherInfo['birthday']);?></b></li>
                                <li>职业:<b><?=$publisherInfo['profession']?></b></li>
                                <li>随游次数:<b><?=$publisherInfo['travelCount']?></b></li>
                                <li><a href="###" class="colGreen">申请理由</a></li>
                            </ul>
                            <a href="javascript:;" tripPublisherId="<?=$publisherInfo['tripPublisherId']?>" class="sureBtn colOrange">移除</a>
                            <!--
                            <a href="javascript:;" class="sureBtn posis colGreen">接受</a>
                            <a href="javascript:;" class="sureBtn colOrange">忽略</a>
                            -->
                        </div>
                    <?php } ?>
                </div>
            <?php }else{ ?>
                <?php foreach($travelInfo['publisherList'] as $publisherInfo){?>
                    <?php if(isset($this->context->userPublisherObj)&&$publisherInfo['publisherId']==$this->context->userPublisherObj->userPublisherId){$joinTravel=true;} ?>
                <?php } ?>
            <?php } ?>
            <div class="web-con">
                <p class="title" id="pinglun"><img src="/assets/images/pinglun2.png" width="25" height="28" style="display: inline-block;">&nbsp;有<span>12</span>条评论</p>
                <div class="zhuanlan-web">
                    <ul id="tanchu_pl">
                    </ul>
                    <ol id="spage">
                    </ol>
                </div>
                <a href="###" class="zl-btn colGreen more">更多评论</a>

                <div class="zhuanlan-text clearfix">
                    <textarea id="pinglun" placeholder="说点什么吧"></textarea>
                    <a href="javascript:;" class="zl-btn bgGreen colWit" onclick="submitComment()">发表评论</a>
                </div>
            </div>


            <p class="title02"><img src="/assets/images/ss.png" width="20" height="20">&nbsp;相似推荐</p>
            <div class="clearfix">
                <?php if($relateRecommend!=null&&count($relateRecommend)>0){?>
                    <?php foreach ($relateRecommend as $trip) {?>
                        <?php if($trip['tripId']==$travelInfo['info']['tripId']){continue;} ?>
                        <div class="web-tuijian fl" style="cursor: pointer" onclick="window.location.href='<?=\common\components\SiteUrl::getTripUrl($trip['tripId'])?>'">
                            <a href="javascript:;" class="pic">
                                <img src="<?=$trip['titleImg']?>" width="270" height="176">
                                <p class="p4"><span>￥<?=intval($trip['basePrice'])?></span>
                                    <?=$trip['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?'每次':'每人'?>
                                </p>
                            </a>
                            <p><?=mb_strlen($trip['title'],"UTF-8")>20?mb_substr($trip['title'],0,20,'UTF-8')."...":$trip['title']?></p>
                            <p class="xing">
                                <img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <span><?=$trip['tripCount']?>人去过</span><span>20条评论</span>
                            </p>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="web-right">
        <div class="kuang clearfix">
            <h3 class="title bgGreen clearfix"><span class="colOrange fl">￥<?=$travelInfo['info']['basePrice']?></span>
                <span class="colWit fr"><?=$travelInfo['info']['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?'每次':'每人'?></span></h3>
            <ul class="ul01 clearfix">
                <li class="tit"><span>出发日期</span><span>起始时间</span><span class="last">&nbsp;人数</span></li>
                <li class="tit tit02"><span><input type="text"></span><span><input type="text"></span>
                    <span class="last">
                     <select>
                        <?php for($i=1;$i<=$travelInfo['info']['maxUserCount'];$i++){echo '<option value="'.$i.'">'.$i.'</option>';} ?>
                     </select>
                    </span>
                </li>
            </ul>
            <?php foreach($travelInfo['serviceList'] as $key=> $service){  ?>
                <p>附加服务</p>
                <ul class="ul02 clearfix" id="serviceLi">
                    <li>
                        <span><?=$service['title']?></span><span><b>￥<?=intval($service['money'])?></b></span>
                        <span class="last"><?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?'每人':'每次' ?>
                            <input type="checkbox"  class="radio" id="radio<?=$service['serviceId']?>" serviceId="<?=$service['serviceId']?>" servicePrice="<?=$service['money']?>" serviceType="<?=$service['type']?>" >
                            <label for="radio<?=$service['serviceId']?>" ><?=$service['title']?></label>
                        </span>
                    </li>
                </ul>
            <?php } ?>
            <p class="colOrange money">￥<?=intval($travelInfo['info']['basePrice']);?></p>
            <input id="toBuy" type="button" value="立即预定" class="btn web-btn6 bgOrange" <?=$isOwner?'disabled style="background-color: #ddd"':''?> >
            <input id="toApply" type="button" value="申请加入" class="btn web-btn5 bgGreen" <?=$isOwner||isset($joinTravel)?'disabled style="background-color: #ddd"':''?> >
            <a href="###" class="colGreen fr">如何预订？</a>
        </div>

        <div class="kuang clearfix">
            <div class="user bgGreen">
                <div class="user-name">
                    <img src="<?=$createUserInfo->headImg;?>" alt="" class="user-pic">
                    <span><?=$createUserInfo->nickname;?></span>
                </div>
                <p><?=$createUserInfo->intro;?></p>
                <a href="javascript:;" onclick="Main.showSendMessage('<?=$createUserInfo->userSign;?>')" class="icon"></a>
            </div>
            <div class="text clearfix">
                <h3>分享</h3>
                <div class="share bdsharebuttonbox">
                    <a href="javascript:;" class="icon sina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="javascript:;" class="icon weixin" data-cmd="weixin" title="分享到微信"></a>
                    <a href="javascript:;" class="icon qq" data-cmd="qzone" title="分享到QQ空间"></a>
                </div>
                <?php if(empty($attention)||$attention==false){?>
                    <p class="colGreen adds"><i class="addIicon" attentionIdTrip="0" id="collection_trip"></i>添加到心愿单</p>
                <?php  }else{?>
                    <p class="colGreen adds"><i class="addIicon active" attentionIdTrip="<?php echo $attention['attentionId']?>" id="collection_trip"v></i>从心愿单中移除</p>
                <?php }?>
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
    var basePrice='<?=intval($travelInfo['info']['basePrice']);?>';
    var basePriceType='<?=$travelInfo['info']['basePriceType'];?>';
    var maxPeopleCount='<?=$travelInfo['info']['maxUserCount'];?>';
    var stepPriceJson='<?=$stepPriceJson;?>';
    var serviceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT;?>';
    var serviceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE;?>';
    var userPublisherId='<?= $this->context->userPublisherObj!=null?$this->context->userPublisherObj->userPublisherId:''?>';
    var userId='<?= $this->context->userObj!=null?$this->context->userObj->userSign:''?>';
    var isOwner='<?=$isOwner;?>';
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
            if(userId==''){
                $("#denglu").click();
                //Main.showTip("登录后才能购买哦~！");
                return;
            }
            if(userPublisherId==''){

                Main.showTip("成为随友后才能加入线路！");
                window.location.href='/index/create-travel';
                return;
            }
            window.location.href='/trip/to-apply-trip?trip='+$("#tripId").val();
        });

        $("#toBuy").bind("click",function(){
            if(userId==''){
                $("#denglu").click();
                //Main.showTip("登录后才能购买哦~！");
                return;
            }
            $("html,body").animate({scrollTop: $("#buyTrip").offset().top-30}, 500);
        });
        if(isOwner==1){

        }
        $("#addOrder").bind("click",function(){
            if(isOwner==1){
                Main.showTip("您无法购买自己的随游哦~");
                return;
            }
            var peopleCount=$("#peopleCount").val();
            var tripId=$("#tripId").val();
            var beginDate=$("#beginTime").val();
            var startTime=$("#startTime").val();
            var serviceArr=[];
            var serviceIds='';


            if(userId==''){
                $("#denglu").click();
                //Main.showTip("登录后才能购买哦~！");
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
            var tripId=$("#tripId").val();
            if(!confirm("确认要删除随友的申请吗？")){
                return;
            }
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
        //隐藏日期选择
        $(document).bind("click",function(e){
            var target = $(e.target);
            if(target.closest("#beginTime").length != 1){
                if(target.closest(".datetimepicker").length == 0){
                    $(".datetimepicker").hide();
                }
            }
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
        var stepFlag=false;

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
        var stepPriceList=[];
        if(stepPriceJson!=''){
            stepPriceList=eval("("+stepPriceJson+")");
        }
        if(basePriceType==TripBasePriceType.TRIP_BASE_PRICE_TYPE_COUNT){
            allPrice=basePrice;
        }else{
            if(stepPriceList.length>0){
                for(var i=0;i<stepPriceList.length;i++){
                    var stepPrice=stepPriceList[i];
                    if(peopleCount>=stepPrice['minCount']&&peopleCount<=stepPrice['maxCount']){
                        allPrice=parseInt(stepPrice['price'])*peopleCount;
                        stepFlag=true;
                        break;
                    }
                }
            }else{
                allPrice=parseInt(basePrice)*peopleCount;
            }
            if(!stepFlag){
                allPrice=parseInt(basePrice)*peopleCount;
            }
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
        allPrice=parseInt(allPrice);
        $("#allPrice").html("￥"+allPrice);
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
        if($('#collection_trip').attr('class')=='addIicon'){
            $('#collection_trip').addClass('active');
            isCollection = true;
        }else{
            $('#collection_trip').removeClass('active');
            isCollection=false;
        }

        if(isCollection){
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
                    $('#collection_trip').removeClass('active');
                    isCollection=false;
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        Main.showTip("收藏成功");
                        $('#collection_trip').attr('attentionIdTrip',data.data);
                    }else{
                        Main.showTip(data.data);
                        $('#collection_trip').removeClass('active');
                        isCollection=false;
                    }
                }
            });
        }else{
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
                    $('#collection_trip').addClass('active');
                    isCollection = true;
                    Main.showTip("收藏随游失败");
                },
                success:function(data){
                    //hide load
                    data=eval("("+data+")");
                    if(data.status==1){
                        Main.showTip("取消成功");
                    }else{
                        $('#collection_trip').addClass('active');
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
        rid=0;
        rSign='';
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
                        var r="@";
                        if(obj.data[i].rTitle==null)
                        {
                            r='';
                        }else
                        {
                            r+=obj.data[i].rTitle;
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
                        if(obj.data[i].travelCount>0){
                            str+='<b>玩过该路线</b>';
                        }
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

                    $("#spage li").click(function() {
                        var page=$(this).find('a').attr('page');
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
        var i =s.indexOf('@');
        if(i==-1){
            var content= s;
            var t='';
        }else
        {

            var j=s.indexOf(':');
            if(j==-1){
                var content= s;
                var t='';
            }else
            {
            var t=s.slice(0,j);
            var content= s.slice(j);
            }
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
        $(obj).addClass('active');
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
                var data=eval('('+data+')');
                if(data.status==1)
                {
                    //Main.showTip("发表成功。。。");
                    getComment(page);
                }else
                {
                    Main.showTip(data.data);
                    $(obj).removeClass('active');

                }
            }
        });
    }


    window._bd_share_config = {
        common : {
            bdText : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['intro']))?>',
            bdDesc : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['title']))?>',
            bdUrl : '<?=Yii::$app->params['base_dir'].'/view-trip/info?trip='.$travelInfo['info']['tripId'];?>&',
            bdPic : '<?=$travelInfo['info']['titleImg']?>'
        },
        share : [{
            "bdSize" : 16
        }]
    }

    //以下为js加载部分
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>