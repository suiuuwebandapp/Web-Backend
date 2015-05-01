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
        <img src="/assets/images/mdd1.fw.png" alt="">
        <ul id="mdd-btn">
            <?php
                foreach($desInfo['scenicList'] as $key=> $scenic){
                    if($key%2==0){
            ?>
                        <li class="mdd-active"><p class="mdd-btn-left">小熊博物馆</p><span class="mdd-btn-right">10:00</span></li>
            <?php }else{ ?>
                        <li><span class="mdd-btn-left">10:00</span><p class="mdd-btn-right">日本奈良公园</p></li>
            <?php
                    }
                }
            ?>
        </ul>
    </div><!--mdd-left end-->
    <div class="mdd-right"><!--mdd-right begin-->
        <div class="mdd-right-box" id="mdd-right-box"><!--mdd-right-box begin-->
            <div style=" display:block;" class="show show1"><!--show1 begin-->
                <p>
                    <span class="span-left">AM:9:00</span>
                    <span class="span-right">AM:10:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
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
            <div class="show show2"><!--show2 begin-->
                <p>
                    <span class="span-left">AM:10:00</span>
                    <span class="span-right">AM:11:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
                <div class="mdd-tuijian">
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
            </div><!--show2 end-->
            <div class="show show3"><!--show3 begin-->
                <p>
                    <span class="span-left">AM:11:00</span>
                    <span class="span-right">AM:12:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
                <div class="mdd-tuijian">
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
            </div><!--show3 end-->
            <div class="show show4"><!--show4 begin-->
                <p>
                    <span class="span-left">AM:12:00</span>
                    <span class="span-right">PM:13:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
                <div class="mdd-tuijian">
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
            </div><!--show4 end-->
            <div class="show show5"><!--show5 begin-->
                <p>
                    <span class="span-left">AM:13:00</span>
                    <span class="span-right">AM:14:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
                <div class="mdd-tuijian">
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

            </div><!--show5 end-->
            <div class="show show6"><!--show6 begin-->
                <p>
                    <span class="span-left">AM:14:00</span>
                    <span class="span-right">PM:15:00</span>
                </p>
                <img src="/assets/images/mdd3.fw.png" alt=""/>
                <div class="show-posi">asadada</div>
                <div class="mdd-tuijian">
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

            </div><!--show6 end-->
        </div><!--mdd-right-box end-->
        <a href="javascript:;" class="mdd-prev" id="mdd-prev"></a>
        <a href="javascript:;" class="mdd-next" id="mdd-next"></a>
    </div><!--mdd-right end-->
</div>
<!--mdd end-->