
    <script src="/assets/other/weixin/js/mobiscroll.core.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.util.datetime.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetimebase.js"></script>
    <script src="/assets/other/weixin/js/mobiscroll.datetime.js"></script>

    <!-- Mobiscroll JS and CSS Includes -->
    <link rel="stylesheet" href="/assets/other/weixin/css/mobiscroll.custom-2.14.4.min.css" type="text/css" />
    <script src="/assets/other/weixin/js/mobiscroll-2.14.4-crack.js"></script>

    <script type="text/javascript">
        $(document).bind("mobileinit", function () {
            //覆盖的代码
            $.mobile.ajaxEnabled = false;
            $.mobile.hashListeningEnabled = false;
            //$.mobile.linkBindingEnabled = false;
        });
    </script>
    <script type="text/javascript">

        $(function () {
            var now = new Date(),
                year = now.getFullYear(),
                month = now.getMonth();
            // Mobiscroll Calendar initialization

            $('#dateList').mobiscroll().calendar({
                theme: 'mobiscroll',  // Specify theme like: theme: 'ios' or omit setting to use default
                lang: 'zh',           // Specify language like: lang: 'pl' or omit setting to use default
                display: 'bottom',    // Specify display mode like: display: 'bottom' or omit setting to use default
                counter: true
            });
        });
    </script>


    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">生日</p>
        <a href="javascript:;" class="sures" onclick="submitUserInfo()">确定</a>
    </div>
    <div class="con cshezhi_ziliaoSet clearfix">
        <input id="dateList" class="sdate" placeholder="请选择出行日期 ..." value="<?=$userInfo["birthday"]?> "/>
    </div>
    <form style="display: none" action="/wechat-user-info/update-user-info" method="post" id="userInfo" >
        <input name="birthday" id="val_sub">
        <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
    </form>
<script>
    function submitUserInfo()
    {
        var val= $("#dateList").val();
        if(val=="")
        {alert("请选择生日");return;}
        $("#val_sub").val(val);
        $("#userInfo").submit();
    }
</script>