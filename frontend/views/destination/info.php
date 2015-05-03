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
                        <div>
                            <a href="suiyou_xiangqing.html"><img src="/assets/images/mdd4.fw.png" alt=""/></a>
                            <h4>小熊博物馆</h4>
                            <p>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                            </p>
                            <font>总价:<a>800</a></font>
                        </div>
                        <div>
                            <a href="#"><img src="/assets/images/mdd4.fw.png" alt=""/></a>
                            <h4>小熊博物馆</h4>
                            <p>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                            </p>
                            <font>总价:<a>800</a></font>
                        </div>
                        <div>
                            <a href="#"><img src="/assets/images/mdd4.fw.png" alt=""/></a>
                            <h4>小熊博物馆</h4>
                            <p>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                                <span><img src="/assets/images/start1.fw.png" alt=""></span>
                            </p>
                            <font>总价:<a>800</a></font>
                        </div>
                    </div>
                </div><!--show1 end-->
            <?php } ?>
        </div><!--mdd-right-box end-->
        <a href="javascript:;" class="mdd-prev" id="mdd-prev"></a>
        <a href="javascript:;" class="mdd-next" id="mdd-next"></a>
    </div><!--mdd-right end-->
</div>
<!--mdd end-->