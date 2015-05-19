<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午9:42
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<script src="/assets/js/mdd.js"></script>
<script src="/assets/js/move.js"></script>
<div class="mdd" id="mdd-main"><!--mdd begin-->
    <div class="mdd-left"><!--mdd-left begin-->
        <img src="<?=$desInfo['info']['titleImg']?>" alt="">
        <ul id="mdd-btn">
            <?php
                foreach($desInfo['scenicList'] as $key=> $scenic){
                    if($key%2==0){
            ?>
                        <li <?=$key==0?'class="mdd-active"':''; ?>><p class="mdd-btn-left"><?=$scenic['title']?></p><span class="mdd-btn-right"><?=$scenic['beginTime']?></span></li>
            <?php }else{ ?>
                        <li><span class="mdd-btn-left"><?=$scenic['beginTime']?></span><p class="mdd-btn-right"><?=$scenic['title']?></p></li>
            <?php
                    }
                }
            ?>
        </ul>
    </div><!--mdd-left end-->
    <div class="mdd-right"><!--mdd-right begin-->
        <div class="mdd-right-box" id="mdd-right-box"><!--mdd-right-box begin-->

            <?php foreach($desInfo['scenicList'] as $key=> $scenic) { ?>
                <div  <?= $key==0?'style="display:block;"':''; ?> class="show show1"><!--show1 begin-->
                    <p>
                        <span class="span-left"><?= \common\components\DateUtils::convertTimePicker($scenic['beginTime'],2) ?></span>
                        <span class="span-right"><?= \common\components\DateUtils::convertTimePicker($scenic['endTime'],2) ?></span>
                    </p>
                    <a href="javascript:;"><img src="<?= $scenic['titleImg'] ?>" alt=""/></a>
                    <div class="show-posi"><?= $scenic['intro'] ?></div>
                    <div class="mdd-tuijian">
                        <?php if($relateRecommend!=null&&count($relateRecommend)>0){?>
                            <?php foreach ($relateRecommend as $trip) {?>
                                <div>
                                    <a href="/view-trip/info?trip=<?=$trip['tripId']?>"><img src="<?=$trip['titleImg']?>" alt=""/></a>
                                    <h4><?=strlen($trip['title']>5?substr($trip['title'],0,5)."...":$trip['title'])?></h4>
                                    <p>
                                        <span><img src="<?= $trip['score']>=2?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13"></span>
                                        <span><img src="<?= $trip['score']>=4?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13"></span>
                                        <span><img src="<?= $trip['score']>=6?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13"></span>
                                        <span><img src="<?= $trip['score']>=8?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13"></span>
                                        <span><img src="<?= $trip['score']>=10?'/assets/images/start1.fw.png':'/assets/images/start2.fw.png'; ?>" width="13" height="13"></span>
                                    </p>
                                    <font><a><?=$trip['basePrice']?></a> 人/次</font>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div><!--show1 end-->
            <?php } ?>
        </div><!--mdd-right-box end-->
        <a href="javascript:;" class="mdd-prev" id="mdd-prev"></a>
        <a href="javascript:;" class="mdd-next" id="mdd-next"></a>
    </div><!--mdd-right end-->
</div>
<!--mdd end-->
