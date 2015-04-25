<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/25
 * Time : 下午3:00
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<link rel="stylesheet" type="text/css" href="/assets/css/my_select.css">
<script type="text/javascript" src="/assets/js/squid.js"></script>
<script type="text/javascript" src="/assets/js/jselect-1.0.js"></script>

<!--初始化select-->
<script type="text/javascript">
    $(function(){
        squid.swing.jselect();

    })
</script>
<!-------随友注册------>
<div class="syRegister">
    <span>邮箱:</span>
    <input type="text" value="" class="syzcy-text">
    <span>密码:</span>
    <input type="password" value="" class="syzcy-text">
    <span>确认密码:</span>
    <input type="password" value="" class="syzcy-text">
    <span>城市:</span>
    <input type="text" value="" class="syzcy-text">
    <div class="imgPic"><img src="" alt="上传身份证"></div>
    <input type="button" value="上传" class="schuan">
    <span>手机:</span>
    <div class="phone-select">
        <div class="sect">
            <select data-enabled="false">
                <option value="zg" class=" selected">区号</option>
                <option value="mg">0345</option>
                <option value="hg">3456</option>
                <option value="zg">6777</option>
                <option value="mg">7777</option>
                <option value="hg">7754</option>
            </select>
        </div>
        <input type="text" value="" class="phone fl" >
    </div>
    <p class="p1">
        <span class="fl">输入验证码</span>
        <input type="text" class="text fl">
        <input type="button" value="获取验证码" class="btn fl">
    </p>
    <p class="p1 agree">
        <input name="" type="radio" value="" id="rad"><label for="rad">同意</label><a href="javascript:;">《网站注册协议》</a>
    </p>
    <input type="button" value="注册" class="zbtn">
</div>
<!-------随友注册------>
