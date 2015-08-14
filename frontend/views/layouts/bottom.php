<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 下午1:18
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<!-----footer------>

<div id="footer-out" class="clearfix bgGreen">
    <div class="footer w1200 clearfix">
        <div class="left fl">
            <a href="<?=Yii::$app->params['base_dir']; ?>"><img src="/assets/images/footer-pic.png" width="174" ></a>
        </div>
        <dl class="fl middle">
            <dt>关于我们</dt>
            <dd><a href="/static?about-aboutSuiuu">关于随游</a></dd>
            <dd><a href="/static?about-concatUs">联系我们</a></dd>
            <dd><a href="/static?about-joinUs">加入随游</a></dd>
        </dl>
        <dl class="fl ">
            <dt>帮助中心</dt>
            <dd><a href="/static?help-newUser">新手上路</a></dd>
            <dd><a href="/static?help-userFlow">游客指南</a></dd>
            <dd><a href="/static?help-publisherFlow">随友指南</a></dd>
            <dd><a href="/static?help-question">常见问题</a></dd>
            <dd><a href="/static?help-feedback">反馈</a></dd>

        </dl>
        <dl class="fl ">
            <dt>网站条款</dt>
            <dd><a href="/static?agreement-service">服务协议</a></dd>
            <dd><a href="/static?agreement-copyright">版权声明</a></dd>
            <dd><a href="/static?agreement-disclaimer">免责声明</a></dd>
        </dl>
        <dl class="fl ">
            <dt>发现</dt>
            <dd><a href="javascript:;">Android 客户端</a></dd>
            <dd><a href="javascript:;"> ios 客户端</a></dd>

        </dl>
    </div>
    <div class="box w1200 clearfix" style="padding-top: 30px">
        <ul class="clearfix list">
            <li><a href="javascript:;" class="icon sina"></a></li>
            <li class="wei"><a href="javascript:;" class="icon weixin"></a><img src="/assets/images/weixinCoda.png" width="130" height="135" class="weixinCoda"></li>
            <li><a href="javascript:;" class="icon db"></a></li>
            <li><a href="javascript:;" class="icon ff"></a></li>
        </ul>

    </div>


    <div class="line">
        <p class="copyright">
            © 2015 Suiuu.com All Rights Reserved.
            <a target="_blank" href="www.miitbeian.gov.cn">沪ICP备15030059号-1</a>
        </p>
    </div>

</div>
<!-----footer--end---->


<!-----------邮件弹层--------------->
<div class="mask" id="myMask"></div>
<div id="showMessageDiv" class="smessages screens" style="display: none;z-index: 100000;">
    <form id="sendMessageForm">
        <input id="show_message_receiverId" type="hidden"/>
        <div class="top clearfix">
           <a href="#" class="userPic"><img id="show_message_headImg" src="/assets/images/5.png" class="pic"></a> <span class="name" id="show_message_nickname">irrt95</span>
            <p class="p1">性别: <b id="show_message_sex">女</b>&nbsp;&nbsp;&nbsp;<b id="show_message_age">90后</b>&nbsp;&nbsp;&nbsp;<b id="show_message_city">德国慕尼黑</b></p>
        </div>
        <textarea id="sendMessageContent"></textarea>
        <a href="javascript:;" class="btn" onclick="Main.showScreenSendMessage()">发送</a>
    </form>
</div>
<!-----------取消订单弹层--------------->
<div id="showOrderDiv" class="scancelTip screens" style="display: none">
    <p>随游非常认真地对待随友取消订单</p>
    <p>我们强烈建议您不要取消订单，因为取消预定会给其他用户造成很大不便。</p>
    <p>请在随游网站上主动联系预订者，解释您取消订单的原因</p>
    <input id="show_message_cancel_order_id" type="hidden"/>
    <textarea placeholder="请填写取消原因" id="show_order_message"></textarea>
    <p class="blue">点击确认后订单将会立即取消，随游的工作人员会在稍后联系您</p>
    <a href="javascript:publisherCancelOrder();" class="btn">确认取消</a>
</div>
<!-----------取消订单弹层--------------->
<div id="showRefundDiv" class="scancelTip screens" style="display: none">
    <p>请填写您的退款原因</p>
    <input id="show_message_refund_order_id" type="hidden"/>
    <textarea placeholder="请填写退款原因" id="show_refund_message"></textarea>
    <p class="blue">点击确认后请耐心等待审核，随游的工作人员会在稍后同您联系</p>
    <a href="javascript:refundOrderByMessage();" class="btn">提交申请</a>
</div>


<!------sy活动详情弹出层------>
<div class="sydetailPop screens">
    <div class="title"></div>
    <div class="text">
        <p class="p1">活动时间</p>
        <p class="p2">2015年6月1日—2015年9月30日</p>
        <p class="p1">活动内容</p>
        <p>凡在随游网成功预定随游产品的用户即<br>可获赠由游友提供的出境随身WIFI（1天）</p>
        <p class="p1">活动规则</p>
        <p>1.用户在成功预定随游产品后，无需单独领取
            小游会根据您的订单联系方式，尽快与您联
            系安排WIFI设备领取事宜</p>
        <p>2.WIFI领取可选择机场自提，或邮寄方式</p>
        <p>3.WIFI设备由游友移动提供，产品本身任何问题，请登陆进行咨询</p>
        <p>4.在法律许可范围内，随游网对本次活动拥有最终解释权。</p>
    </div>
</div>

<?php if (!isset($this->context->userObj)){ ?>
    <div class="myLogins screens clearfix" style="display: none;">
        <ol class="top myTit">
            <li><a href="#" class="a1">手机注册</a></li>
            <li><a href="#" class="a2">登录</a></li>
            <li><a href="#" class="a3">邮箱注册</a></li>
        </ol>
        <div class="down clearfix">
            <div class="left">
                <div class="box myCon box1">
                    <ul>
                        <li><input id="nickname_top" type="text" value="" maxlength="20" placeholder="昵称"></li>
                        <li class="country">
                            <select id="codeId_top" name="countryIds" class="areaCodeSelect_top" required>
                                <option value=""></option>
                                <?php if($this->context->countryList!=null){ ?>
                                    <?php foreach ($this->context->countryList as $c) { ?>
                                        <?php if(empty($c['areaCode'])){continue;} ?>
                                        <?php if ($c['areaCode'] == $this->context->areaCode) { ?>
                                            <option selected
                                                    value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </li>
                        <li><input id="phone_top" type="text" value="" maxlength="30" placeholder="手机号"></li>
                        <li><input id="phone_password_top" type="password" value="" maxlength="30" placeholder="密码"></li>
                        <li class="zrow">
                            <input id="valNum" type="text" class="code" placeholder="图形验证码" maxlength="4">
                            <img id="codeImg" src="/index/get-code" onclick="javascript:$(this).attr('src','/index/get-code')" alt="" class="codeimg">
                        </li>
                        <li class="zrow">
                            <input id="phoneCode_top" type="text" class="code" placeholder="手机验证码" maxlength="6">
                            <a href="javascript:;" id="getCodePhoneRegister" class="pas">获取验证码</a>
                        </li>
                        <li><a href="javascript:;" class="btn blue" id="phoneRegister">注册</a></li>
                        <li class="rows">
                            <input type="checkbox" id="zhuce-check01">
                            <label for="zhuce-check01" class="fleft">同意</label>
                            <a target="_blank" href="/static?agreement-service" class="agreen" style="margin-left: 10px">服务协议</a>
                            <a target="_blank" href="/static?agreement-copyright" class="agreen">版权声明</a>
                            <a target="_blank" href="/static?agreement-disclaimer" class="agreen">免责声明</a>
                        </li>
                    </ul>
                </div>

                <div class="box myCon box2">
                    <ul>
                        <li><input type="text" placeholder="邮箱/手机号" id="username_bottom" maxlength="30"></li>
                        <li><input type="password" placeholder="密码" id="userpassword_bottom" maxlength="30"></li>
                        <li id="code9527" style="display: none">
                            <script async type="text/javascript" src="http://api.geetest.com/get.php?gt=b3a60a5dd8727fe814b43fce2ec7412a"></script>
                        </li>
                        <li><a href="javascript:;" onclick="login()" id="login-check" class="btn">登录</a></li>
                        <li class="rows">
                            <a href="/index/password-send-code" class="forgot">忘记密码</a>
                            <input type="checkbox" id="logo-check">
                            <label for="logo-check" class="fright">自动登录</label>
                        </li>
                    </ul>
                </div>
                <div class="box myCon box3">
                    <ul>
                        <li><input id="regNickname" type="text" value="" maxlength="20" placeholder="昵称"></li>
                        <li><input type="text" placeholder="邮箱" id="regEmail" maxlength="30"></li>
                        <li><input type="password" placeholder="密码" id="regEmailPwd" maxlength="30"></li>
                        <li><a href="javascript:;" id="emailRegister" class="btn blue">注册</a></li>
                        <li id="emailTime" class="shuaxin"><input type="button" value="发送成功，" style="width: 288px"/></li>
                        <li class="rows">
                            <input type="checkbox" id="zhuce-check02">
                            <label for="zhuce-check02" class="fleft">同意</label>
                            <a target="_blank" href="/static?agreement-service" class="agreen" style="margin-left: 10px">服务协议</a>
                            <a target="_blank" href="/static?agreement-copyright" class="agreen">版权声明</a>
                            <a target="_blank" href="/static?agreement-disclaimer" class="agreen">免责声明</a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="right">
                <a href="/access/connect-wechat" class="icon wei"></a>
                <a href="/access/connect-weibo" class="icon sina"></a>
                <a href="javascript:alert('QQ登录暂缓开通');" class="icon qq"></a><!--/access/connect-qq-->
            </div>
        </div>
    </div>
<?php  } ?>

<script type="text/javascript">
    var isLogin=<?=empty($this->context->userObj)?0:1;?>;
    $(document).ready(function(){
        $(".activityBanner").bind("click",function(){
            $(".sydetailPop").show();
            $(".mask").show();
        });
    });
</script>
