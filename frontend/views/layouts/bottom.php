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