<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 下午1:18
 * Email: zhangxinmailvip@foxmail.com
 */
$travelInfo=json_decode($orderInfo->tripJsonInfo,true);
$serviceInfo=json_decode($orderInfo->serviceInfo,true);
?>
<div id="checkOut" class="w1200 clearfix">
    <div class="check clearfix">
        <dl class="checkList  clearfix">
            <dt class="title">
                <span>订单</span><span>随游</span><span>开始时间</span><span>坐标</span><span>随游时长</span><span>随友</span><span>出行日期</span><span>人数</span><span>单项服务</span>
            </dt>
            <dd>
                <span class="pic"><img src="<?=$travelInfo['info']['titleImg']?>"></span>
                <span><?=$travelInfo['info']['title']?></span>
                <span><?=\common\components\DateUtils::convertTimePicker($orderInfo->startTime,2)?></span>
                <span><?=$travelInfo['info']['countryCname']."-".$travelInfo['info']['cityCname']?></span>
                <span>
                    <?=$travelInfo['info']['travelTime'] ?>
                    <?=$travelInfo['info']['travelTimeType']==\common\entity\TravelTrip::TRAVEL_TRIP_TIME_TYPE_DAY?'天':'小时';?>
                </span>
                <span>
                    <a href="#" class="user"> <img src="<?=$travelInfo['createPublisherInfo']['headImg']?>" ></a>
                    <a href="#" class="message"><b><?=$travelInfo['createPublisherInfo']['nickname']?></b><br>
                        <img src="/assets/images/xf.fw.png" width="18" height="12">
                    </a>
                </span>
                <span><?=$orderInfo->beginDate?></span>
                <span><?=$orderInfo->personCount?></span>
                <span>
                <?php if(!empty($serviceInfo)){?>
                    <?php foreach($serviceInfo as $service){ ?>
                        <?=$service['title']?><br>
                <?php }} ?>
                </span>
            </dd>
        </dl>
        <p><a href="#" class="btn">结算</a><span>总价：<b><?=$orderInfo->totalPrice?></b></span></p>
    </div>
</div>
