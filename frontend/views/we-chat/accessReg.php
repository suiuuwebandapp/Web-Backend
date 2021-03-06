<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript">
    var bindFlag="<?=$bindFlag?>";
    if(bindFlag==0){
        alert("您的第三方信息已经超时，请重新登录");
        window.location.href="/we-chat/login";
    }
</script>
<style>
    .formTip{
        float: right;
        margin-right: 80px;
        font-size: 14px;
        height: 2.7rem;
    }
    .accAreaCodeSelect{
        font-size: 16px;
    }
    .accAreaCodeSelect .select2-choice{
        border-radius:0px !important;
        background: #eee !important;
        color: #858585;
        text-align: center;
        font-size: 0.85rem;
        height: 2.7rem;
    }
    .accAreaCodeSelect #select2-chosen-1{
        margin-top: 5px;
    }
    .select2-hidden-accessible{
        display: none;
    }
</style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">注册绑定</p>
    </div>
<div class="con Registered02 clearfix">
    <ul class="lists clearfix">
        <li>
            <label for="">昵称</label>
            <input type="text" id="nickname" value="<?=$userBase->nickname?>" >
        </li>
        <li>
            <label for="">设定密码</label>
            <input type="password" id="password">
        </li>
        <li>
            <label for="">区号</label>
            <select id="accCodeId" name="countryIds" class="accAreaCodeSelect"  required>
                <option value=""></option>
                <?php if($countryList!=null){ ?>
                    <?php foreach ($countryList as $c) { ?>
                        <?php if(empty($c['areaCode'])){continue;} ?>
                        <?php if ($c['areaCode'] == $areaCode) { ?>
                            <option selected
                                    value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </select>
        </li>
        <li>
            <label for="">手机号</label>
            <input type="text" id="phone">
        </li>

        <li class="clearfix" >
            <label for="">手机验证码</label>
            <input type="text"  style="width:70%; float:left;"  id="code"><a href="javascript:;" style="height:2.7rem; width:30%; overflow:hidden;font-size:0.85rem; float:right;  text-decoration:none; text-align:center; line-height:2.7rem;color:#73b9ff;" onclick="getCode()">获取验证码</a>
        </li>
    </ul>
    <a href="javascript:;" class="btn" id="register">立即注册</a>
    <p class="agr"><input type="checkbox" id="agreement"><label for="agreement">同意<a href="###">《网站注册协议》</a></label></p>
    <input id="r_url" hidden="hidden" value="<?php echo Yii::$app->session->get('r_url');?>">
</div>

<script>
    function changeCode()
    {
        $('#codeImg').attr('src','/index/get-code')
    }
    $(document).ready(function () {
        //初始化区号选择
        $(".accAreaCodeSelect").select2({
            'width':'100%',
            'height':'100%',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });

    });
    var isClick=false;
    function getCode()
    {
        var phone = $('#phone').val();
        var areaCode = $("#accCodeId").val();
        //var valNum = $('#valNum').val();
        if(isClick)
        {
            alert("已经发送请稍后");
            return;
        }
        if(areaCode=="")
        {
            alert('国家不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
       /* if(valNum=="")
        {
            alert('图型验证码不能为空');
            return;
        }*/
        isClick=true;
        $.ajax({
            url :'/we-chat/acc-send-message',
            type:'post',
            data:{
                areaCode:areaCode,
                phone:phone
            },
            error:function(){
                alert("验证码发送失败");
                isClick=false;
            },
            success:function(data){
                isClick=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("发送成功，请注意查收");
                }else{
                    alert(data.data);
                }
            }
        });
    }

    $('#register').bind("click",function(){
        if(isClick)
        {
            alert("注册中...");
            return;
        }
        var password = $('#password').val();
        var code =$('#code').val();
        var nickname = $('#nickname').val();
        if(code=="")
        {
            alert('验证码不能为空');
            return;
        }
        if(password=="")
        {
            alert('密码不能为空');
            return;
        }
        if(nickname=="")
        {
            alert('昵称不能为空');
            return;
        }
        if(!$('#agreement').is(':checked'))
        {
            alert('请同意网站注册协议');
            return;
        }
        isClick=true;
        $.ajax({
            url :'/we-chat/access-reg',
            type:'post',
            data:{
                code:code,
                password:password,
                nickname:nickname
            },
            error:function(){
                isClick=false;
                alert("注册失败");
            },
            success:function(data){
                isClick=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    if($('#r_url').val())
                    {
                        window.location.href=$('#r_url').val();
                        return;
                    }
                    window.location.href="/wechat-trip/index";
                }else{
                    alert(data.data);
                }
            }
        });
    })
</script>