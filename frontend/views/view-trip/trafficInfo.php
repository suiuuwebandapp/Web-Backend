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

<style type="text/css">
    .datepicker{
        top: 335px !important;
        position: fixed !important;
        background-color: #ffffff;
        border:1px solid #cccccc ;
    }
    body{
        background-color: #F7F7F7;
    }
</style>
<?php $isOwner=$this->context->userPublisherObj!=null&&$this->context->userPublisherObj->userPublisherId==$travelInfo['info']['createPublisherId']?true:false; ?>
<!--交通详情页-->
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
            <li><a href="#detail">服务简介</a></li>
            <li><a href="#price">车辆信息</a></li>
            <li><a href="#pinglunCount">评论</a></li>
        </ul>
    </div>
</div>
<div class="sydetail jtdetail w1200 clearfix">
    <div class="titTop clearfix fl">
        <?php if($isOwner){ ?>
            <a href="<?=\common\components\SiteUrl::getEditTripUrl($travelInfo['info']['tripId'])?>" class="change">修改随游</a>
        <?php }?>
        <h3 class="title"><?=$travelInfo['info']['title'];?></h3>
        <p><img src="/assets/images/position.png" width="14" height="18">&nbsp;<?=$travelInfo['info']['countryCname']?>，<?=$travelInfo['info']['cityCname']?></p>
        <p class="xing">
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
                <ul class="details clearfix">
                    <li><span class="icon icon1">服务时间
                            <b>
                                <?php if(empty($travelInfo['info']['startTime'])){ ?>
                                    全天24小时提供服务
                                <?php }else{ ?>
                                    <?=\common\components\DateUtils::formatTime($travelInfo['info']['startTime']);?> -
                                    <?=\common\components\DateUtils::formatTime($travelInfo['info']['endTime']);?>
                                <?php } ?>
                            </b>
                        </span></li>
                    <li><span class="icon icon2">全天可包车<b id="tripTime"><?=$travelInfo['info']['travelTime'];?></b>小时</span></li>
                    <li class="last"><span class="icon icon3">最多乘坐<b id="maxUserCount"><?=$travelInfo['trafficInfo']['seatCount'];?></b>人</span></li>
                </ul>
                <p class="title02" id="detail">服务简介</p>
                <div>
                    <?=str_replace("\n","</br>",$travelInfo['info']['info']);?>
                </div>

                <p class="title02" id="price">车辆信息</p>
                <div class="contian car clearfix">
                    <span><b class="icon icon01"></b>车型:<b><?=$travelInfo['trafficInfo']['carType'];?></b></span>
                    <span><b class="icon icon02"></b>司机驾龄:<b><?=date("Y",time())-date("Y",strtotime($travelInfo['trafficInfo']['driverLicenseDate']));?>年</b></span>
                    <span><b class="icon icon03"></b>携带宠物:<b><?=$travelInfo['trafficInfo']['allowPet']==1?'允许':'不允许';?></b></span>
                    <span><b class="icon icon05"></b>全天包车时长:<b><?=$travelInfo['trafficInfo']['serviceTime'];?>小时</b></span>
                    <span><b class="icon icon07"></b>乘客吸烟:<b><?=$travelInfo['trafficInfo']['allowSmoke']==1?'允许':'不允许';?></b></span>
                    <span><b class="icon icon08"></b>每日公里限:<b><?=$travelInfo['trafficInfo']['serviceMileage'];?>公里</b></span>
                    <span><b class="icon icon09"></b>行李空间:<b><?=is_numeric($travelInfo['trafficInfo']['spaceInfo'])?$travelInfo['trafficInfo']['spaceInfo'].'件行李':$travelInfo['trafficInfo']['spaceInfo'];?></b></span>
                    <span><b class="icon icon010"></b>儿童座椅:<b><?=$travelInfo['trafficInfo']['childSeat']==1?'有':'无';?></b></span>
                    <span><b class="icon icon011"></b>最大载客:<b><?=$travelInfo['trafficInfo']['seatCount'];?>人</b></span>
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

                <p class="title02">预订须知</p>
                <?php if(!empty($travelInfo['trafficInfo']['nightServicePrice'])){ ?>
                    <p>夜间服务：时间为<?=\common\components\DateUtils::formatTime($travelInfo['trafficInfo']['nightTimeStart'])?>-<?=\common\components\DateUtils::formatTime($travelInfo['trafficInfo']['nightTimeEnd'])?>，接机加收<?=$travelInfo['trafficInfo']['nightServicePrice']?>元每趟/服务费。</p>
                <?php } ?>
                <?php if(!empty($travelInfo['info']['scheduledTime'])){ ?>
                    <p>预订时间：提前<?=$travelInfo['info']['scheduledTime']/(60*60*24)?>天进行预订</p>
                <?php } ?>
                <p>超时费用：超时每小时<?=$travelInfo['trafficInfo']['overTimePrice'];?>元</p>
                <p>超程费用：每公里收费<?=$travelInfo['trafficInfo']['overMileagePrice'];?>元</p>

                <ul class="detNav tabTitle clearfix">
                    <li><a href="javascript:;" class="icon icon01 active">预定流程</a></li>
                    <li><a href="javascript:;" class="icon icon02">退款说明</a></li>
                    <li><a href="javascript:;" class="icon icon03">保险保障</a></li>
                </ul>
                <div class="detCon con01 tabCon" style="display:block;">
                    <div class="line"></div>
                    <p><span>1</span> 咨询随游的设计师，确认游玩细节。</p>
                    <p><span>2</span> 填写日期，人数等信息并预支付订单。</p>
                    <p><span>3</span> 等待陪同随友接单后，通过邮件，短信及站内信方式收到订单提醒。</p>
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
        <div class="kuang clearfix">
            <h3 class="title bgGreen clearfix"><span class="colOrange fl">￥<?=$travelInfo['info']['basePrice']?></span><span class="colWit fr">每次</span></h3>
            <?php if($isOwner){ ?>
                <input type="button" value="申请预订" class="btn bgOrange" style="background-color: #ddd">
            <?php }else{ ?>
                <input type="button" value="申请预订" class="btn bgOrange" id="trafficOrderBtn">
            <?php } ?>
            <a href="javascript:;" class="colGreen helps">如何预订？</a>
        </div>

        <div class="kuang clearfix">
            <div class="user bgGreen">
                <div class="user-name">
                    <a target="_blank" href="<?=\common\components\SiteUrl::getViewUserUrl($createUserInfo->userSign)?>">
                        <img src="<?=$createUserInfo->headImg;?>" alt="" class="user-pic">
                    </a>
                    <span><?=$createUserInfo->nickname;?></span>
                </div>
                <p>随游设计师<a href="javascript:;" class="help"> </a></p>
                <script type="text/javascript">
                    $(function(){
                        $('.sydetail .web-right .kuang .user p .help').hover(function(e) {
                            $('.sydetail .web-right .kuang .tip').toggle();
                        });
                    })
                </script>
                <div class="tip">
                    <p>随游设计师仅提供本行程路线的制定，我们会另外安排合适的随友陪伴您游玩</p>
                </div>
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
                    <p class="colGreen adds"><i class="addIicon active" attentionIdTrip="<?php echo $attention['attentionId']?>" id="collection_trip"></i>从心愿单中移除</p>
                <?php }?>
            </div>
        </div>
    </div>

</div>

<div class="jtPro screens clearfix" id="trafficOrderDiv" style="z-index: 1001">
    <h2 class="title">车辆服务<a href="javascript:;" id="orderDivClose" class="close"></a></h2>
    <div class="contains">
        <div class="left">
            <p>选择服务</p>
            <ul class="ul01 clearfix">
                <?php if(!empty($travelInfo['trafficInfo']['carPrice'])){ ?>
                    <li>
                        <a href="javascript:;" class="btn active">包车</a>
                    </li>
                <?php } ?>
                <?php if(!empty($travelInfo['trafficInfo']['airplanePrice'])){ ?>
                    <li>
                        <a href="javascript:;" class="btn">接机</a>
                    </li>
                <?php } ?>
                <?php if(!empty($travelInfo['trafficInfo']['airplanePrice'])){ ?>
                    <li>
                        <a href="javascript:;" class="btn">送机</a>
                    </li>
                <?php } ?>

            </ul>
            <ul class="ul01 clearfix">
                <li>
                    <p>预约日期（当地）</p>
                    <div class="selet">
                        <input type="text" id="orderDate" placeholder="请选择日期" readonly/>
                    </div>
                </li>
                <li>
                    <p>时间（当地）</p>
                    <div class="selet">
                        <input type="text" data-field="time" id="orderTime" placeholder="请选择时间" readonly/>
                    </div>
                </li>
                <li>
                    <p>人数</p>
                    <div class="selet">
                        <select id="peopleCount">
                            <?php for($i=1;$i<=$travelInfo['info']['maxUserCount'];$i++){ ?> <option value="<?=$i?>"><?=$i?>人</option> <?php } ?>
                        </select>
                    </div>
                </li>
            </ul>
            <p class="p1" id="addService" style="width: 150px;cursor: pointer"><span>添加服务</span><a href="javascript:;" class="adds" ></a></p>

        </div>
        <div class="right">
            <div class="noChoseService">未选择服务</div>
            <div id="trafficServiceList" style="display: none">
            </div>
            <p class="all"><span class="name">总价</span> <span class="money" id="allPrice">￥0</span></p>
            <form action="/user-order/add-traffic-order" method="post" id="trafficOrder">
                <input type="hidden" id="tripId" name="tripId" value="<?=$travelInfo['info']['tripId']?>"/>
                <input type="hidden" id="serviceList" name="serviceList"/>

                <a href="javascript:;" id="toPay" class="btn">支付</a>
            </form>
        </div>
    </div>
</div>

<div id="choseDateBox"></div>

<script type="text/javascript">
    var basePrice='<?=intval($travelInfo['info']['basePrice']);?>';
    var basePriceType='<?=$travelInfo['info']['basePriceType'];?>';
    var maxPeopleCount='<?=$travelInfo['info']['maxUserCount'];?>';
    var serviceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT;?>';
    var serviceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE;?>';
    var userPublisherId='<?= $this->context->userPublisherObj!=null?$this->context->userPublisherObj->userPublisherId:''?>';
    var userId='<?= $this->context->userObj!=null?$this->context->userObj->userSign:''?>';
    var isOwner='<?=$isOwner;?>';
    var rid=0;
    var tripId=$("#tripId").val();
    var page=1;
    var rSign='';
    var nowDate='<?=date('Y-m-d',time()+(empty($travelInfo['info']['scheduledTime'])?0:$travelInfo['info']['scheduledTime'])); ?>';
    var minTime=<?=empty($travelInfo['info']['startTime'])?'null':"'".$travelInfo['info']['startTime']."'"?>;
    var maxTime=<?=empty($travelInfo['info']['endTime'])?'null':"'".$travelInfo['info']['endTime']."'"?>;
    var type=<?=empty($travelInfo['info']['type'])?0:$travelInfo['info']['type'];?>;
    var nightTimeStart=<?=empty($travelInfo['trafficInfo']['nightTimeStart'])?'null':"'".$travelInfo['trafficInfo']['nightTimeStart']."'";?>;
    var nightTimeEnd=<?=empty($travelInfo['trafficInfo']['nightTimeEnd'])?'null':"'".$travelInfo['trafficInfo']['nightTimeEnd']."'";?>;
    var nightServicePrice=<?=empty($travelInfo['trafficInfo']['nightServicePrice'])?'null':$travelInfo['trafficInfo']['nightServicePrice'];?>;
    var carPrice=<?=empty($travelInfo['trafficInfo']['carPrice'])?'null':$travelInfo['trafficInfo']['carPrice'];?>;
    var airplanePrice=<?=empty($travelInfo['trafficInfo']['airplanePrice'])?'null':$travelInfo['trafficInfo']['airplanePrice'];?>;

    window._bd_share_config = {
        common : {
            bdText : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['intro']))?>',
            bdDesc : '随游网-<?=htmlspecialchars(str_replace("\n"," ",$travelInfo['info']['title']))?>',
            bdUrl : '<?=\common\components\SiteUrl::getTripUrl($travelInfo['info']['tripId']);?>',
            bdPic : '<?=$travelInfo['info']['titleImg']?>'
        },
        share : [{
            "bdSize" : 16
        }]
    };
    //以下为js加载部分
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" ></script>
<script type="text/javascript" src="/assets/plugins/datetimepicker/DateTimePicker.js"></script>
<script type="text/javascript" src="/assets/pages/view-trip/info.js" ></script>

