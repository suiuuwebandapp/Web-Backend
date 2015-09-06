<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/13
 * Time : 下午6:59
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<div class="suiyouHelp w1200">
    <div class="top">
        <p>首先，为您的分享精神点个赞！</p>
        <p>随游，是由像您一样热爱分享的朋友所发布的目的地体验</p>
    </div>
    <div class="down clearfix">
        <div class="introduce fl">
            <ul class="icon clearfix">
                <li><div class="pic">
                        <img src="/assets/images/sy01.png" width="70" height="58">
                        <p class="p1">发布随游</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>通过发布照片与文字描述，把您所热爱的景点,活,经历分享给更多的旅行者。</span></p>
                        <p><span class="bgGreen icon"></span><span>通过实名验证确保身份的真实性。让来自远方的朋友跟随您放心体验。</span></p>
                        <p><span class="bgGreen icon"></span><span>通过发布随游项目或成为兼职向导，利用闲暇时间轻松赚取收入</span></p>
                    </div>
                </li>
                <li>
                    <div class="pic">
                        <img src="/assets/images/sy02.png" width="75" height="71">
                        <p class="p1">参与随游</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>也许您想发布的随游已经存在？ 没关系，找到您感兴趣的随游并且申请加入成为该随游的向导。</span></p>
                        <p><span class="bgGreen icon"></span><span>说明你想加入该随游的原因，等待发布者批准吧！</span></p>
                        <p><span class="bgGreen icon"></span><span>带领各地朋友去体验你所加入的随游，轻松获得收入。</span></p>
                    </div>
                </li>
                <li class="nomg">
                    <div class="pic">
                        <img src="/assets/images/sy03.png" width="67" height="61">
                        <p class="p1">管理随游</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>自由设置价格，接受或拒绝订单，一切由您做主。</span></p>
                        <p><span class="bgGreen icon"></span><span>管理其他随友发出的加入申请，招募更多伙伴为你发布的随游服务。</span></p>
                        <p><span class="bgGreen icon"></span><span>即使更新随游内容，积极发布更多随游，赚取路线分成。</span></p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <?php if (isset($this->context->userObj)) { ?>
        <?php if($this->context->userObj->isPublisher){ ?>
            <a href="javascript:;" onclick="openCreateTravelDiv()" class="btn bgGreen colWit">现在就试试吧！</a>
        <?php }else { ?>
            <a href="/user-info/create-travel" class="btn bgGreen colWit">现在就试试吧！</a>

        <?php } ?>
    <?php }else{ ?>
        <a href="javascript:;" onclick="$('#denglu').click()" class="btn bgGreen colWit">现在就试试吧！</a>
    <?php } ?>
</div>

<div class="syhPro screens w1200 clearfix" style="display: none;">
    <div class="top">
        <p>先为您要发布的随游选个类型吧</p>
    </div>
    <div class="down clearfix">
        <div class="introduce fl">
            <ul class="icon clearfix">
                <li onclick="jumpCreateTravel(1)">
                    <div class="pic">
                        <img src="/assets/images/syP01.png" width="29" height="50">
                        <p class="p1">慢行探索</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>借助步行和公共交通的方法探索城市</span></p>
                        <p><span class="bgGreen icon"></span><span>与游客分享风光背后的故事</span></p>
                    </div>
                </li>
                <li onclick="jumpCreateTravel(1)">
                    <div class="pic">
                        <img src="/assets/images/syP02.png" width="37" height="57">
                        <p class="p1">个性玩法</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>独一无二的玩法&amp;体验当地人才知道的精彩</span></p>
                        <p><span class="bgGreen icon"></span><span>带游客融入你喜欢的生活</span></p>
                    </div>
                </li>
                <li onclick="jumpCreateTravel(2)" class="nomg">
                    <div class="pic">
                        <img src="/assets/images/syP03.png" width="53" height="44">
                        <p class="p1">交通服务</p>
                    </div>
                    <div class="text clearfix">
                        <div class="line last bgGreen"></div>
                        <p><span class="bgGreen icon"></span><span>一切只为便捷舒心的旅程</span></p>
                        <p><span class="bgGreen icon"></span><span>用你的交通工具改变别人的旅行</span></p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">

    function openCreateTravelDiv(){
        $(".syhPro").show();
        $(".mask").show();
    }

    function jumpCreateTravel(type){
        window.location.href="/user-info/create-travel?t="+type;
    }
</script>