<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/28
 * Time : 下午2:03
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<style>
    .grzy .con .right .PL li .picRight{
        padding: 20px 0 10px 0px;
    }
    .grzy .con .right .PL li .picRight p{
        margin-top: 10px;
    }
    .grzy .con .right .PL li .line{
        margin-top: 10px;
    }

</style>
<div class="grzy w1200 clearfix">
    <div class="top clearfix">
        <div class="Pic"><a href="javascript:;" class="userPic"><img src="<?=$userInfo['headImg']?>"></a></div>
        <div class="user">
            <p class="p2"><span class="userName"><?=$userInfo['nickname']?></span>
                <a href="javascript:;" class="btn" onclick="Main.showSendMessage('<?=$userInfo['userSign']?>')">联系TA</a>
                <?php if(!empty($this->context->userObj)&&$userInfo['userSign']==$this->context->userObj->userSign){ ?>
                <a href="/user-info?tab=userInfo" class="change">编辑个人信息</a></p>
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
            <p>个人简介：<?=nl2br($userInfo['info'])?></p>
        </div>
    </div>
    <div class="con clearfix">
        <div class="left">
            <div class="box">
                <?php
                $authPhone = false;
                $authEmail = false;
                $authUser = false;
                $authExperience = false;

                if (!empty($userInfo['phone'])) {
                    $authPhone = true;
                }
                if (!empty($userInfo['email'])) {
                    $authEmail = true;
                }
                if(!empty($userCard)&&$userCard->status==\common\entity\UserCard::USER_CARD_STATUS_SUCCESS){
                    $authUser=true;
                }
                if(!empty($userAptitude)&&$userAptitude->status==\common\entity\UserAptitude::USER_APTITUDE_STATUS_SUCCESS){
                    $authExperience = true;
                }
                ?>
                <h2 class="bgGreen top">验证方式</h2>
                <ul>
                    <li <?=$authPhone?'class="have"':''; ?>>邮箱验证</li>
                    <li <?=$authEmail?'class="have"':''; ?>>电话验证</li>
                    <li <?=$authUser?'class="have"':''; ?>>实名验证</li>
                    <li <?=$authExperience?'class="have"':''; ?>>经历资质</li>
                </ul>

            </div>
            <div class="down clearfix">
                <?php if(!empty($tripList)){ ?>
                    <p><span class="icon icon01"></span><span><?= count($tripList); ?>条相关随游 </span></p>
                <?php } ?>
                <p><span class="icon icon02"></span><span><?=$commentList['msg']->totalCount;?>条相关评论</span> </p>
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
                            <a href="<?=\common\components\SiteUrl::getViewUserUrl($trip['userSign'])?>" class="user"><img src="<?=$trip['headImg'];?>" ></a>
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
            <?php if(!empty($commentList['data'])){ ?>
            <h2 class="title01">相关评论： </h2>
            <div class="PL clearfix">
                <ul id="commentList">
                    <?php foreach($commentList['data'] as $comment){ ?>
                        <li>
                            <a href="<?=\common\components\SiteUrl::getViewUserUrl($comment['userSign'])?>" class="Pic"><img src="<?=$comment['headImg'] ?>"></a>
                            <div class="picRight">
                                <span class="name"><?=$comment['nickname']?></span>
                                <p><?=$comment['content'] ?></p>
                                <div class="line clearfix">
                                    <span class="datas"><?=date("Y-m-d H:s",strtotime($comment['cTime']))?></span>
                                    <a href="<?=\common\components\SiteUrl::getTripUrl($comment['tripId'])?>" class="a1"><?=$comment['title']?></a>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <?php if($commentList['msg']->currentPage*$commentList['msg']->pageSize<$commentList['msg']->totalCount){ ?>
                    <p class="more"><a href="javascript:;" id="showMoreBtn" showPage="<?=$commentList['msg']->currentPage+1;?>">查看更多</a></p>
                <?php } ?>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    var userId='<?=$userInfo['userSign']?>';
    $(document).ready(function(){
        $("#showMoreBtn").bind("click",function(){
            var page=$(this).attr("showPage");
            getUserComment(page);
        });
    });

    function getUserComment(page){
        $.ajax({
            url :'/view-user/get-user-comment-list',
            type:'post',
            data:{
                p:page,
                u:userId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取用户评论失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    var html='';
                    for(var i=0;i<data.data.data.length;i++){
                        var comment=data.data.data[i];

                        html+='<li>';
                        html+='<a href="'+comment.userSign+'" class="Pic"><img src="'+comment.headImg+'"></a>';
                        html+='<div class="picRight">';
                        html+='<span class="name">'+comment.nickname+'</span>';
                        html+='<p>'+comment.content+'</p>';
                        html+='<div class="line clearfix">';
                        html+='<span class="datas">'+Main.formatDate(comment.cTime,'yyyy-MM-dd hh:mm')+'</span>';
                        html+='<a href="'+comment.tripId+'" class="a1">'+comment.title+'</a>';
                        html+=' </div>';
                        html+='</div>';
                        html+='</li>';
                    }
                    $("#commentList").append(html);

                    var msg=data.data.msg;
                    if(msg.currentPage*msg.pageSize>msg.totalCount){
                        $("#showMoreBtn").hide();
                    }else{
                        $("#showMoreBtn").attr("showPage",parseInt(msg.currentPage)+1);
                    }
                }else{
                    Main.showTip("获取用户评论失败");
                }
            }
        });
    }

</script>