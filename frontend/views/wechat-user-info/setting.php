
    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">设置</p>
    </div>
    <div class="con cshezhi clearfix">
        <p class="title">个人信息</p>
        <div class="box"  onclick="to('/wechat-user-info/info')">
            <div class="user clearfix">
                <a href="javascript:;" class="pic"><img src="<?= $userInfo['headImg'];?>"></a>
                <span class="name"><?= $userInfo['nickname'];?></span>
            </div>
        </div>
        <p class="title">关于随游</p>
        <div class="box" id="list">
            <ul class="list">
                <li onclick="to('/wechat-user-info/supply')">我们提供</li>
                <li onclick="to('/wechat-user-info/notice')">订购须知</li>
                <li onclick="to('/wechat-user-info/contact')">联系我们</li>
            </ul>
        </div>
    </div>

<script>
    function to(url)
    {
        window.location.href=url;
    }
</script>