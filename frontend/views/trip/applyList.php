<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/5
 * Time : 下午8:48
 * Email: zhangxinmailvip@foxmail.com
 */
?>


<div class="syManage clearfix">
    <div class="up clearfix">
        <h3 class="title"><?=$travelInfo['info']['countryCname']."-".$travelInfo['info']['cityCname'];?></h3>
        <div class="pic fl">
            <img src="<?=$travelInfo['info']['titleImg'];?>" alt="">
        </div>
        <div class="pic-right fl">
            <h3 class="title02"><?=$travelInfo['info']['title'];?></h3>
            <dl>
                <dt>基本价：<b><?=$travelInfo['info']['basePrice'];?></b></dt>
                <dt>评分:&nbsp;&nbsp;
                    <img src="<?= $travelInfo['info']['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                    <img src="<?= $travelInfo['info']['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                    <img src="<?= $travelInfo['info']['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                    <img src="<?= $travelInfo['info']['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                    <img src="<?= $travelInfo['info']['score']==10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13">
                </dt>
                <?php if($travelInfo['serviceList']!=null){ ?>
                    <?php foreach($travelInfo['serviceList'] as $serviceInfo){ ?>
                        <dd>
                            <?=$serviceInfo['title']?>：<b><?=$serviceInfo['money']?></b>
                            <?=$serviceInfo['title']==\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?'/次':'/人';?>
                        </dd>
                    <?php } ?>
                <?php } ?>
            </dl>
        </div>
    </div>
    <div class="newTip">
        <input type="button" value="申请消息">
        <span>2</span>
    </div>
    <div class="newsLists clearfix">
        <?php if($applyList!=null){ ?>

            <?php foreach($applyList as $apply){ ?>
                <div class="lists" id="apply_div_<?=$apply['applyId']?>"">
                    <img src="<?=$apply['headImg']?>" width="66" height="66" alt="">
                    <ul>
                        <li><?=$apply['nickname']?></li>
                        <li>性别:<b><?php if($apply['sex']==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($apply['sex']==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b></li>
                        <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($apply['birthday'])?></b></li>
                        <li>职业:<b><?=$apply['profession']?></b></li>
                        <li>随游次数:<b><?=$apply['travelCount']?></b></li>
                        <li class="last">
                            <a href="javascript:;"><img src="/assets/images/xf.fw.png" alt=""></a>
                            <p><?=str_replace("\n","</br>",$apply['info']);?></p>
                        </li>
                    </ul>
                    <a href="javascript:;" applyId="<?=$apply['applyId']?>" class="removeBtn">拒绝</a>
                    <a href="javascript:;" applyId="<?=$apply['applyId']?>" publisherId="<?=$apply['publisherId']?>" class="sure">同意</a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".removeBtn").bind("click",function(){
            var applyId=$(this).attr("applyId");
            opposeApply(applyId);
        });

        $(".sure").bind("click",function(){
            var applyId=$(this).attr("applyId");
            var publisherId=$(this).attr("publisherId");
            agreeApply(applyId,publisherId);
        });
    });

    /**
     * 同意加入随游
     * @param applyId
     * @param publisherId
     */
    function agreeApply(applyId,publisherId)
    {
        $.ajax({
            url :'/trip/agree-apply',
            type:'post',
            data:{
                applyId:applyId,
                publisherId:publisherId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("同意加入随游失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    Main.showTip("同意加入随游成功");
                    $("#apply_div_"+applyId).remove();
                }else{
                    Main.showTip("同意加入随游失败");
                }
            }
        });
    }


    /**
     * 拒绝加入随游
     * @param applyId
     */
    function opposeApply(applyId)
    {
        $.ajax({
            url :'/trip/oppose-apply',
            type:'post',
            data:{
                applyId:applyId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("拒绝加入随游失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    Main.showTip("拒绝加入随游成功");
                    $("#apply_div_"+applyId).remove();
                }else{
                    Main.showTip("拒绝加入随游失败");
                }
            }
        });
    }
</script>