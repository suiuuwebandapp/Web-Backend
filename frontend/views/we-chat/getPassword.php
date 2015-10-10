
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
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
        padding: 10px;
        height: 2.7rem;
    }

    .select2-hidden-accessible{
        display: none;
    }
</style>
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">找回密码</p>
    </div>
<div class="con findPassword02 clearfix">
    <ul class="lists clearfix">
        <li>
            <label>区号</label>
            <select id="accCodeId" name="countryIds" class="accAreaCodeSelect"  >
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
            <input  id="phone" type="text" placeholder="手机号">
            <a href="javascript:;" class="code colOrange" onclick="getCode()">获取验证码</a>
        </li>
        <li>
            <label>验证码</label>
            <input id="code" type="text" >
        </li>
        <li>
            <label>输入新密码</label>
            <input id="password" type="password" >
        </li>
    </ul>
    <a href="javascript:;" class="btn" onclick="updatePassword()">确定</a>

</div>
<script>
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
    var isSend=false;
    function getCode()
    {
        var phone = $('#phone').val();
        var areaCode = $("#accCodeId").val();
        if(areaCode=="")
        {
            alert('区号不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        if(isSend)
        {
            alert("发送中...");
            return;
        }
        isSend=true;
        $.ajax({
            url :'/we-chat/password-code',
            type:'post',
            data:{
                areaCode:areaCode,
                _csrf: $('input[name="_csrf"]').val(),
                phone:phone
            },
            error:function(){
                isSend=false;
                alert("验证码发送失败");
            },
            success:function(data){
                isSend=false;
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
    function updatePassword()
    {
        var phone = $('#phone').val();
        var code = $("#code").val();
        var password = $("#password").val();
        if(code=="")
        {
            alert('验证码不能为空');
            return;
        }
        if(phone=="")
        {
            alert('手机号不能为空');
            return;
        }
        if(password=="")
        {
            alert('密码不能为空');
            return;
        }
        if(isSend)
        {
            alert("修改中...");
            return;
        }
        isSend=true;
        $.ajax({
            url :'/we-chat/update-password',
            type:'post',
            data:{
                code:code,
                password:password,
                _csrf: $('input[name="_csrf"]').val(),
                phone:phone
            },
            error:function(){
                isSend=false;
                alert("修改失败");
            },
            success:function(data){
                isSend=false;
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    alert("修改成功即将跳转");
                    window.location.href="/wechat-user-center";
                }else{
                    alert(data.data);
                }
            }
        });
    }
</script>