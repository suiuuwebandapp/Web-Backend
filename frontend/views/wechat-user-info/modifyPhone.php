
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
            background: #FFFFFF !important;
            color: #858585;
            text-align: center;
            font-size: 0.85rem;
            height: 2.7rem;
            margin-bottom:10px;
        }
        .accAreaCodeSelect #select2-chosen-1{
            margin-top: 15px;
        }
        .select2-hidden-accessible{
            display: none;
        }
    </style>


    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">手机验证</p>
        <a href="javascript:;" class="sures" onclick="validatePhone()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <select id="accCodeId" class="accAreaCodeSelect"  required>
            <option value=""></option>
            <?php if($countryList!=null){
                $userInfo["areaCode"]=empty($userInfo["areaCode"])?"+86":$userInfo["areaCode"];
                ?>
                <?php foreach ($countryList as $c) { ?>
                    <?php if ($c['areaCode'] == $userInfo["areaCode"]) { ?>
                        <option selected
                                value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " /" . $c['areaCode'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " /" . $c['areaCode'] ?></option>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </select>
        <input type="text" id="phone" placeholder="输入手机号">
        <div class="line">
            <input type="text" id="code" placeholder="手机验证码">
            <a href="javascript:;" class="codes" onclick="getPhoneCode()">获取验证码</a>

        </div>
        <p class="tip">仅当您和另一名随游用户确认预订时，此资料才会被分享。这是我们帮
            助大家联系彼此的方式</p>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="wechat" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
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

    function getPhoneCode()
    {
        var phone = $('#phone').val();
        var areaCode = $("#accCodeId").val();
        //var valNum = $('#valNum').val();
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
        $.ajax({
            url :'/wechat-user-info/send-code',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                areaCode:areaCode,
                phone:phone
            },
            error:function(){
                alert("验证码发送失败");
            },
            success:function(data){
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

    function validatePhone()
    {
        var phone = $('#phone').val();
        var code = $("#code").val();
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
        $.ajax({
            url :'/wechat-user-info/validate-phone',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val(),
                code:code,
                phone:phone
            },
            error:function(){
                alert("验证失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    window.location.href="/wechat-user-info/info";
                }else{
                    alert(data.data);
                }
            }
        });
    }


</script>