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
        <a href="/user-info/create-travel" class="btn bgGreen colWit">现在就试试吧！</a>
    <?php }else{ ?>
        <a href="javascript:;" onclick="$('#denglu').click()" class="btn bgGreen colWit">现在就试试吧！</a>
    <?php } ?>
</div>