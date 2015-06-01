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


<div id="footer-out" class="clearfix">
    <div class="footer w1200 clearfix">
        <div class="left fl">
            <a href="<?=Yii::$app->params['base_dir']; ?>"><img src="/assets/images/footer-pic.png" width="174" height="122"></a>
        </div>
        <dl class="fl middle">
            <dt>公司信息</dt>
            <dd><a href="/static?about-aboutSuiuu">关于随游</a></dd>
            <dd><a href="/static?about-services">服务协议</a></dd>
            <dd><a href="/static?about-concatUs">联系我们</a></dd>
            <dd><a href="http://www.jobtong.com/e/15004">加入我们</a></dd>
        </dl>
        <dl class="fl ">
            <dt>帮助中心</dt>
            <dd><a href="/static?help-feedback">反馈</a></dd>
            <dd><a href="/static?help-useFlow">使用流程</a></dd>
            <dd><a href="/static?help-refundPolicy">退款政策</a></dd>
        </dl>
        <dl class="fl ">
            <dt>声明</dt>
            <dd><a href="/static?statement-copyright">版权声明</a></dd>
            <dd><a href="/static?statement-disclaimer">免责声明</a></dd>
        </dl>
        <dl class="fl ">
            <dt>随游移动端</dt>
            <dd><a href="#">APP近期发布</a></dd>
        </dl>
        <div class="fr right">
            <ul>
                <li class="fl"><a href="#" class="icon sina"></a><img src="/assets/images/weiboCoda.png" width="110" height="110" class="weiboCoda"></li>
                <li class="fl" id="wei"><a href="#" class="icon weixin"></a><img src="/assets/images/weixinCoda.png" width="110" height="110" class="weixinCoda"></li>
            </ul>

        </div>


    </div>

</div>
<!-----footer--end---->


<!-----------邮件弹层--------------->
<div class="mask" id="myMask"></div>
<div id="showMessageDiv" class="smessages screens" style="display: none">
    <form id="sendMessageForm">
        <input id="show_message_receiverId" type="hidden"/>
        <div class="top">
            <div class="ss"><a href="#" class="userPic"><img id="show_message_headImg" src="/assets/images/5.png" class="pic"></a> <span class="name" id="show_message_nickname">irrt95</span></div>
            <p>性别: <b id="show_message_sex">女</b> <b id="show_message_age">90后</b> <b id="show_message_city">德国慕尼黑</b></p>
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
                            <img id="codeImg" src="/index/get-code" alt="" class="codeimg">
                        </li>
                        <li class="zrow">
                            <input id="phoneCode_top" type="text" class="code" placeholder="手机验证码" maxlength="6">
                            <a href="javascript:;" id="getCodePhoneRegister" class="pas">获取验证码</a>
                        </li>
                        <li><a href="javascript:;" class="btn blue" id="phoneRegister">注册</a></li>
                        <li class="rows">
                            <input type="checkbox" id="zhuce-check01">
                            <label for="zhuce-check01" class="fleft">同意</label>
                            <a target="_blank" href="/static?about-services" class="agreen" style="margin-left: 10px">服务协议</a>
                            <a target="_blank" href="/static?help-refundPolicy" class="agreen">退款政策</a>
                            <a target="_blank" href="/static?statement-copyright" class="agreen">版权声明</a>
                            <a target="_blank" href="/static?statement-disclaimer" class="agreen">免责声明</a>
                        </li>
                    </ul>
                </div>

                <div class="box myCon box2">
                    <ul>
                        <li><input type="text" placeholder="邮箱/手机号" id="username" maxlength="30"></li>
                        <li><input type="password" placeholder="密码" id="userpassword" maxlength="30"></li>
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
                        <li><input type="text" placeholder="邮箱" id="regEmail" maxlength="30"></li>
                        <li><input type="password" placeholder="密码" id="regEmailPwd" maxlength="30"></li>
                        <li><a href="javascript:;" id="emailRegister" class="btn blue">注册</a></li>
                        <li id="emailTime" class="shuaxin"><input type="button" value="发送成功，" style="width: 288px"/></li>
                        <li class="rows">
                            <input type="checkbox" id="zhuce-check02">
                            <label for="zhuce-check02" class="fleft">同意</label>
                            <a target="_blank" href="/static?about-services" class="agreen" style="margin-left: 10px">服务协议</a>
                            <a target="_blank" href="/static?help-refundPolicy" class="agreen">退款政策</a>
                            <a target="_blank" href="/static?statement-copyright" class="agreen">版权声明</a>
                            <a target="_blank" href="/static?statement-disclaimer" class="agreen">免责声明</a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="right">
                <a href="/access/connect-weibo" class="icon wei"></a>
                <a href="/access/connect-wechat" class="icon sina"></a>
                <a href="javascript:alert('QQ登录暂缓开通');" class="icon qq"></a><!--/access/connect-qq-->
            </div>
        </div>
    </div>
<?php  } ?>
