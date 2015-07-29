<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/28
 * Time : 下午2:03
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<div class="grzy w1200 clearfix">
    <div class="top clearfix">
        <div class="Pic"><a href="javascript:;" class="userPic"><img src="<?=$userInfo['headImg']?>"></a></div>
        <div class="user">
            <p class="p2"><span class="userName"><?=$userInfo['nickname']?></span>
                <a href="javascript:;" class="btn" onclick="Main.showSendMessage('<?=$userInfo['userSign']?>')">联系TA</a>
                <?php if($userInfo['userSign']==$this->context->userObj->userSign){ ?>
                <a href="#" class="change">编辑个人信息</a></p>
            <?php } ?>
            <p class="p1">

                <?php if(!empty($userInfo['countryCname'])&&!empty($userInfo['cityCname'])) { ?>
                    <i class="icon"></i><span><?=$userInfo['countryCname']?>，<?=$userInfo['cityCname']?></span>
                <?php }else if(!empty($userInfo['countryCname'])||!empty($userInfo['cityCname'])){ ?>
                    <i class="icon"></i><span> <?=$userInfo['countryCname']?><?=$userInfo['cityCname']?></span>
                <?php } ?>

                <span><?=$userInfo['profession']?></span>
                <span>
                    <?=\common\components\DateUtils::convertBirthdayToAge($userInfo['birthday'])=="保密"?'':\common\components\DateUtils::convertBirthdayToAge($userInfo['birthday'])?>
                </span>
            </p>
            <p>个人简介：<?=$userInfo['info']?></p>
        </div>
    </div>
    <div class="con clearfix">
        <div class="left">
            <div class="box">
                <h2 class="bgGreen top">验证方式</h2>
                <ul>
                    <li <?=empty($userInfo['email'])?'':'class="have"'; ?>>邮箱验证</li>
                    <li <?=empty($userInfo['phone'])?'':'class="have"'; ?>>电话验证</li>
                    <li>实名验证</li>
                    <li>经历资质</li>
                </ul>

            </div>
            <div class="down clearfix">
                <?php if(!empty($tripList)){ ?>
                    <p><span class="icon icon01"></span><span><?= count($tripList); ?>条相关随游 </span></p>
                <?php } ?>
                <p><span class="icon icon02"></span><span>150条相关评论</span> </p>
            </div>
        </div>

        <div class="right">
            <?php if(!empty($tripList)){ ?>
                <h2 class="title01">相关随游： </h2>
                <div class="sy clearfix">
                    <?php foreach($tripList as $trip){?>
                        <div class="web-tuijian fl">
                            <a href="<?=\common\components\SiteUrl::getTripUrl($trip['tripId'])?>" class="pic">
                                <img src="<?=$trip['titleImg']?>" width="410" height="267">
                                <p class="p4"><span>￥<?= intval($trip['basePrice']) ?></span>
                                    <?=$trip['basePriceType']==\common\entity\TravelTrip::TRAVEL_TRIP_BASE_PRICE_TYPE_COUNT?'每次':'每人'?>
                                </p>
                            </a>
                            <a href="javascript:;" class="user"><img src="<?=$trip['headImg'];?>" ></a>
                            <p class="title"><?=mb_strlen($trip['title'],"UTF-8")>20?mb_substr($trip['title'],0,20,"UTF-8")."...":$trip['title'] ?></p>
                            <p class="xing">
                                <img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                                <img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                                <img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                                <img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                                <img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" alt="">
                                <span><?=$trip['tripCount']?>人去过</span><span><?=empty($trip['commentCount'])?'0':$trip['commentCount']?>条评论</span>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <br /><br />
            <?php if(!empty($recommendList)){ ?>
            <h2 class="title01">相关评论： </h2>
            <div class="PL clearfix">
                <ul>
                    <?php foreach($recommendList['data'] as $recommend){ ?>
                        <li>
                            <a href="#" class="Pic"><img src="<?=$recommend['headImg'] ?>"></a>
                            <div class="picRight">
                                <span class="name"><?=$recommend['nickname']?></span>
                                <p><?=$recommend['content'] ?></p>
                                <div class="line clearfix">
                                    <span class="datas"><?=date("Y-m-d H:s",strtotime($recommend['cTime']))?></span>
                                    <a href="#" class="a1"><?=$recommend['title']?></a>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <p class="more"><a href="#">查看更多</a></p>
            <?php } ?>
            </div>
        </div>
    </div>
</div>