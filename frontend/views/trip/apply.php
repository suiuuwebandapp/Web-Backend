<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/3
 * Time : 下午6:06
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<style>
    .trip_info{
        display: block;
    }
    .pic img{
        margin-top: 5px;
    }
    .title{
        font-weight: bold;
    }
</style>
<div class="syApply clearfix">
    <div class="user">
        <div class="user-name">
            <a href="<?=\common\components\SiteUrl::getViewUserUrl($createUserInfo->userSign)?>" target="_blank"><img class="user-pic" alt="" src="<?=$createUserInfo->headImg?>"></a>
            <span><?=$createUserInfo->nickname?></span>
        </div>
        <p><?=$createUserInfo->intro?></p>
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
    <div class="con clearfix">
        <div class="pic">
            <img src="<?=$tripInfo->titleImg?>" alt="">

        </div>
        <div class="right">
            <h2 class="title"><?=$tripInfo->title?></h2>
            <span class="trip_info">随游 <?=$tripInfo->startTime?>-<?=$tripInfo->endTime?></span>
            <span class="trip_info">随游时长 <?=$tripInfo->startTime?>-<?=$tripInfo->endTime?></span>
            <p>
                <?=nl2br($tripInfo->info);?>
            </p>
        </div>
    </div>
    <form method="post" id="applyForm" action="/trip/apply-trip">
        <input type="hidden" name="trip" value="<?=$tripInfo->tripId?>"  />
        <textarea name="info"></textarea>
        <a href="javascript:;" class="btn" id="applyBtn">提交申请</a>
    </form>

</div>

<script>

    $(document).ready(function(){
        $("#applyBtn").bind("click",function(){
            var info=$("#applyInfo").val()
            if(info==''){
                return;
            }
            $("#applyForm").submit();
        });
    });

</script>