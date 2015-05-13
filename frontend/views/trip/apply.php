<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/3
 * Time : 下午6:06
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<div class="syApply clearfix">
    <div class="user">
        <div class="user-name">
            <img class="user-pic" alt="" src="<?=$createUserInfo->headImg?>">
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
            <p>
                <?= str_replace(" ","&nbsp;",str_replace("\n","</br>",$tripInfo->info));?>
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