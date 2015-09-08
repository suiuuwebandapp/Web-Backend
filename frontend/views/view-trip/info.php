<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/29
 * Time : 下午6:00
 * Email: zhangxinmailvip@foxmail.com
 */
?>
<link rel="stylesheet" type="text/css" href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/datetimepicker/DateTimePicker.css" />
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="/assets/plugins/datetimepicker/DateTimePicker-ltie9.css" />
<script type="text/javascript" src="/assets/plugins/datetimepicker/DateTimePicker-ltie9.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/assets/pages/view-trip/info.css" />

<?php $isOwner=$this->context->userPublisherObj!=null&&$this->context->userPublisherObj->userPublisherId==$travelInfo['info']['createPublisherId']?true:false; ?>
<div class="sydetailBanner web-banner" id="imgs">
    <div class="banner">
        <ul class="clearfix">
            <?php foreach($travelInfo['picList'] as $pic ){?>
                <li style="width:auto"><a href="javascript:;"><img src="<?= $pic['url'];?>" height="335px" alt=""></a></li>
            <?php }?>
        </ul>
    </div>
    <a href="javascript:;" class="pre"></a>
    <a href="javascript:;" class="next"></a>
</div>

<div class="bgGreen sydetailNav clearfix" id="tripInfoMapUrl">
    <div class="w1200 clearfix">
        <ul class="clearfix">
            <li><a href="#imgs">照片</a></li>
            <li><a href="#detail">详情描述</a></li>
            <li><a href="#price">价格内容</a></li>
            <li><a href="#pinglunCount">评论</a></li>
        </ul>
    </div>
</div>

<div class="sydetail w1200 clearfix">
    <div class="titTop clearfix fl">
        <?php if($isOwner){ ?>
            <a href="<?=\common\components\SiteUrl::getEditTripUrl($travelInfo['info']['tripId'])?>" class="change">修改随游</a>
        <?php }?>
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
                        <div><img src="<?=$special['picUrl']?>" style="max-width: 830px"></div>
                        <p class="title"><?=$special['title']?></p>
                        <p><?=$special['info']?></p>
                    <?php } ?>
                <?php } ?>
                <?php if($userRecommend!=null&&!empty($userRecommend['content'])){ ?>
                    <div class="bgGreen idea clearfix">
                        <a href="javascript:;" class="cir fl"><img src="<?=$userRecommend['headImg'];?>" width="66" height="66"></a>
                        <div class="fl">
                            <p>推荐理由：</p>
                            <p><?=nl2br($userRecommend['content']);?></p>
                        </div>
                    </div>
                <?php } ?>
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

            <?php if($isOwner&&(count($travelInfo['publisherList'])>1||!empty($applyList))){?>
                <div class="newsLists clearfix" id="publisherList">
                    <h2 class="title line">随游管理</h2>
                    <?php foreach($travelInfo['publisherList'] as $publisherInfo){?>
                        <?php if($publisherInfo['publisherId']==$travelInfo['info']['createPublisherId']){continue;} ?>
                        <?php if(isset($this->context->userPublisherObj)&&$publisherInfo['publisherId']==$this->context->userPublisherObj->userPublisherId){$joinTravel=true;} ?>
                        <div class="lists clearfix" id="div_trip_publisher_<?=$publisherInfo['tripPublisherId']?>" type="publisherList">
                            <img src="<?= $publisherInfo['headImg']?>" alt="" class="userpic">
                            <ul class="clearfix">
                                <li class="li01">
                                    <p class="name"><span><?=$publisherInfo['nickname'];?></span><img src="/assets/images/xf.fw.png" width="18" height="12" style="cursor: pointer" onclick="Main.showSendMessage('<?=$publisherInfo['userSign']?>')"></p>
                                    <p>性别:<b><?php if($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($publisherInfo['sex']==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b></p>
                                </li>
                                <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($publisherInfo['birthday']);?></b></li>
                                <li>职业:<b><?=$publisherInfo['profession']?></b></li>
                                <li>随游次数:<b><?=$publisherInfo['travelCount']?></b></li>
                                <li></li>
                            </ul>
                            <a href="javascript:;" tripPublisherId="<?=$publisherInfo['tripPublisherId']?>" class="sureBtn colOrange">移除</a>
                        </div>
                    <?php } ?>
                    <?php foreach($applyList as $apply){?>
                        <div class="lists clearfix" id="apply_div_<?=$apply['applyId']?>">
                            <img src="<?= $apply['headImg']?>" alt="" class="userpic">
                            <ul class="clearfix">
                                <li class="li01">
                                    <p class="name"><span><?=$apply['nickname'];?></span><img src="/assets/images/xf.fw.png" width="18" height="12" style="cursor: pointer" onclick="Main.showSendMessage('<?=$apply['userSign']?>')"></p>
                                    <p>性别:<b><?php if($apply['sex']==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($apply['sex']==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b></p>
                                </li>
                                <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($apply['birthday']);?></b></li>
                                <li>职业:<b><?=$apply['profession']?></b></li>
                                <li>随游次数:<b><?=$apply['travelCount']?></b></li>
                                <li><a href="javascript:;" data="<?=$apply['info']?>"  onclick="showApplyInfo(this)" class="colGreen">申请理由</a></li>
                            </ul>
                            <a href="javascript:;" class="sureBtn posis colGreen sure" applyId="<?=$apply['applyId']?>" publisherId="<?=$apply['publisherId']?>">接受</a>
                            <a href="javascript:;" class="sureBtn colOrange removeBtn" applyId="<?=$apply['applyId']?>">忽略</a>
                        </div>
                    <?php } ?>
                </div>
            <?php }else{ ?>
                <?php foreach($travelInfo['publisherList'] as $publisherInfo){?>
                    <?php if(isset($this->context->userPublisherObj)&&$publisherInfo['publisherId']==$this->context->userPublisherObj->userPublisherId){$joinTravel=true;} ?>
                <?php } ?>
            <?php } ?>
            <div class="web-con" id="pinglunDiv">
                <p class="title" id="pinglunCount">
                    <img src="/assets/images/pinglun2.png" width="25" height="28" style="display: inline-block;">
                    &nbsp;有<span><?=$travelInfo['info']['commentCount'];?></span> 条评论
                </p>
                <div class="zhuanlan-web">
                    <ul id="tanchu_pl">
                    </ul>
                   <!-- <ol id="spage"> </ol>-->
                </div>
                <a href="javascript:;" class="zl-btn colGreen more" id="showMoreComment">更多评论</a>

                <div class="zhuanlan-text clearfix" id="pllist">
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
                            <p><?=mb_strlen($trip['title'],"UTF-8")>20?mb_substr($trip['title'],0,14,'UTF-8')."...":$trip['title']?></p>
                            <p class="xing">
                                <img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                                <span><?=$trip['tripCount']?>人去过</span><span><?=$trip['commentCount']?>条评论</span>
                            </p>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="web-right">
        <form id="orderForm" method="post" action="/user-order/add-order">
            <input type="hidden" name="tripId" id="tripId" value="<?=$travelInfo['info']['tripId'];?>" />
            <div class="kuang clearfix">
                <h3 class="title bgGreen clearfix"><span class="colOrange fl">￥<?=$travelInfo['info']['basePrice']?></span>
                    <span class="colWit fr"><?=$travelInfo['info']['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?'每次':'每人'?></span></h3>
                <ul class="ul01 clearfix" style="overflow:inherit">
                    <li class="tit"><span>出发日期</span><span>起始时间</span><span class="last">&nbsp;人数</span></li>
                    <li class="tit tit02">
                        <span><input type="text" name="beginDate" id="beginTime" style="padding:0 10px"></span>
                        <span><input type="text" data-field="time" name="startTime" id="startTime" style="padding:0 10px"></span>
                        <span class="last">
                         <select id="peopleCount" name="peopleCount">
                            <?php for($i=1;$i<=$travelInfo['info']['maxUserCount'];$i++){echo '<option value="'.$i.'">'.$i.'</option>';} ?>
                         </select>
                        </span>
                    </li>
                </ul>
                <?php if(!empty($travelInfo['serviceList'])){ ?>
                    <a href="javascript:;" id="showServiceDiv" class="servers"><span>该随游可选择的附加服务</span><b class="icon"></b></a>
                <?php } ?>
                <p class="colOrange money" id="allPrice">总价：￥<?=intval($travelInfo['info']['basePrice']);?></p>
                <input id="toApply" type="button" value="申请加入" class="btn web-btn5 bgGreen" <?=$isOwner||isset($joinTravel)?'disabled style="background-color: #ddd"':''?> >
                <input id="addOrder" type="button" value="立即预定" class="btn web-btn6 bgOrange" <?=$isOwner?'disabled style="background-color: #ddd"':''?> >
                <a href="javascript:;" id="buyHelp" class="colGreen fr">如何预订?</a>
            </div>
        </form>

        <div class="kuang clearfix">
            <div class="user bgGreen">
                <div class="user-name">
                    <a target="_blank" href="<?=\common\components\SiteUrl::getViewUserUrl($createUserInfo->userSign)?>">
                        <img src="<?=$createUserInfo->headImg;?>" alt="" class="user-pic">
                    </a>
                <span><?=$createUserInfo->nickname;?></span>
                </div>
                <p style="max-width: 215px"><?=$createUserInfo->intro;?></p>
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


<div class="serverSelct screens" style="z-index: 1000;">
    <h2 class="bgGreen title">附加服务 <a href="javascript:;" class="close" id="closeServiceDiv"><img src="/assets/images/syxClose.png" width="22" height="22"></a></h2>
    <ul class="ul02 clearfix" id="serviceLi">
        <?php foreach($travelInfo['serviceList'] as $key=> $service){  ?>
            <li>
                <span><?=$service['title']?></span>
                <span><b>￥<?=intval($service['money'])?></b></span>
                <span class="last">
                    <?=$service['type']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?'每人':'每次' ?>
                    <input type="checkbox"  class="radio" id="radio<?=$service['serviceId']?>" serviceId="<?=$service['serviceId']?>" servicePrice="<?=$service['money']?>" serviceType="<?=$service['type']?>" >
                    <label for="radio<?=$service['serviceId']?>" ></label>
                </span>
            </li>
        <?php } ?>
    </ul>
    <a href="javascript:;" class="bgGreen btn" id="confirmServiceDiv">确定</a>
</div>


<div class="screens syxqPro02" style="display: none">
    <div class="tit02 bgGreen">
        <a href="<?=\common\components\SiteUrl::getViewUserUrl($createUserInfo->userSign)?>" class="userPic fl"><img src="<?=$createUserInfo->headImg;?>"></a>
        <div class="text fl">
            <p class="p1"><img src="/assets/images/position.png" width="14" height="18">&nbsp;&nbsp;<?=$travelInfo['info']['countryCname']?>，<?=$travelInfo['info']['cityCname']?></p>
            <p style="margin-top: 15px">&nbsp;<?=$travelInfo['info']['title']?></p>
        </div>
    </div>
    <form method="post" id="applyForm" action="/trip/apply-trip">
    <div class="line">
        <ul class="list clearfix">
            <li>
                <p>在线提交申</p>
                <p>请加入你感兴趣的随游</p>
            </li>
            <li>
                <p>接到订单</p>
                <p>陪伴游客</p>
                <p>完成随游体验</p>
            </li>
            <li class="last">
                <p>完成伴游</p>
                <p>不用费心思</p>
                <p>发布随游</p>
                <p>也能获得收入</p>
            </li>
        </ul>

            <input type="hidden" name="trip" value="<?=$travelInfo['info']['tripId']?>"  />
            <textarea name="info" placeholder="说说你为什么想加入这条随游，怎样为旅行者提供更好的服务？"></textarea>
    </div>
    <div class="btns">
        <a href="javascript:;" class="btn bgOrange fl" id="cancelApply">放弃申请</a>
        <a href="javascript:;" class="btn bgGreen fr" id="applyBtn">提交申请</a>
    </div>
    </form>
</div>
<div id="choseDateBox"></div>

<?php
    $stepPriceJson='';
    if(!empty($travelInfo['priceList'])){
        $stepPriceJson=json_encode($travelInfo['priceList']);
    }
?>

<script type="text/javascript">
    var basePrice='<?=intval($travelInfo['info']['basePrice']);?>';
    var basePriceType='<?=$travelInfo['info']['basePriceType'];?>';
    var maxPeopleCount='<?=$travelInfo['info']['maxUserCount'];?>';
    var stepPriceJson='<?=$stepPriceJson;?>';
    var serviceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT;?>';
    var serviceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE;?>';
    var userPublisherId='<?= $this->context->userPublisherObj!=null?$this->context->userPublisherObj->userPublisherId:''?>';
    var userId='<?= $this->context->userObj!=null?$this->context->userObj->userSign:''?>';
    var isOwner='<?=$isOwner;?>';
    var rid=0;
    var tripId=$("#tripId").val();
    var page=1;
    var rSign='';
    var nowDate='<?=date('Y-m-d',time()); ?>';
    var type=<?=empty($travelInfo['info']['type'])?0:$travelInfo['info']['type'];?>;
    var minTime=<?=empty($travelInfo['info']['startTime'])?'null':"'".$travelInfo['info']['startTime']."'"?>;
    var maxTime=<?=empty($travelInfo['info']['endTime'])?'null':"'".$travelInfo['info']['endTime']."'"?>;

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

<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" ></script>
<script type="text/javascript" src="/assets/plugins/datetimepicker/DateTimePicker.js"></script>
<script type="text/javascript" src="/assets/pages/view-trip/info.js" ></script>

