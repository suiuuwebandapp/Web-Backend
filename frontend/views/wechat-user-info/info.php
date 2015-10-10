    <div class="Uheader header mm-fixed-top">
        <a href="#menu"></a>
        <p class="navTop">设置</p>
    </div>
    <div class="con cshezhi_ziliao clearfix">
        <div class="box" onclick="to('/wechat-user-info/up-view?v=HeadImg')">
            <div class="user clearfix">
                <a href="javascript:;" class="pic"><img src="<?=$userInfo["headImg"]?>"></a>
            </div>
        </div>
        <div class="box">
            <ul class="list">
                <li onclick="to('/wechat-user-info/up-view?v=Nickname')"><span>昵称</span><b><?=$userInfo["nickname"]?></b></li>
                <li onclick="to('/wechat-user-info/up-view?v=Sex')"><span>性别</span><b><?php $str = '';if($userInfo["sex"]==1){$str ="男";}elseif($userInfo["sex"]==0){$str ="女";}else{$str ="保密";} echo $str;?></b></li>
                <li onclick="to('/wechat-user-info/up-view?v=Birthday')"><span>出生日期</span><b><?=empty($userInfo["birthday"])?"待完善":$userInfo["birthday"] ?></b></li>
                <li onclick="to('/wechat-user-info/up-view?v=Site')"><span>常驻地</span><b><?=empty($userInfo["cityCname"])?"待完善":$userInfo["countryCname"]."、".$userInfo["cityCname"]?></b></li>
                <li onclick="to('/wechat-user-info/up-view?v=Work')"><span>职业</span><b><?=empty($userInfo["profession"])?"待完善":$userInfo["profession"]?></b></li>
                <li onclick="to('/wechat-user-info/access')"><span>账号绑定</span>
                    <?php if(empty($access)){?>
                        <p>
                            待完善
                        </p>
                    <?php }else {
                        $wechat=false;
                        $sina=false;
                        $qq=false;
                        foreach($access as $val){
                            if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_WECHAT){
                                $wechat=true;
                            }
                            if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_QQ){
                                $qq=true;
                            }
                            if($val["type"]==\common\entity\UserAccess::ACCESS_TYPE_SINA_WEIBO){
                                $sina=true;
                            }
                        }
                        ?>
                        <?php if ($wechat) { ?>
                            <b class="icon wei"></b>
                        <?php } elseif ($sina) { ?>
                            <b class="icon sina"></b>
                        <?php } elseif ($qq) { ?>
                            <b class="icon qq"></b>
                        <?php }
                    }
                    ?>
                </li>
            </ul>
            <p class="tit"><span>联系方式</span> </p>
            <ul class="list">
                <li  onclick="to('/wechat-user-info/up-view?v=Phone')"><span>手机</span><b><?=empty($userInfo["phone"])?"待完善":$userInfo["phone"]?></b></li>
                <li onclick="to('/wechat-user-info/up-view?v=Email')"><span>邮箱</span><b><?=empty($userInfo["email"])?"待完善":$userInfo["email"]?></b></li>
                <li  onclick="to('/wechat-user-info/up-view?v=Qq')"><span>Q Q</span><b><?=empty($userInfo["qq"])?"待完善":$userInfo["qq"]?></b></li>
                <li  onclick="to('/wechat-user-info/up-view?v=Wechat')"><span>微信</span><b><?=empty($userInfo["wechat"])?"待完善":$userInfo["wechat"]?></b></li>
            </ul>
            <p  onclick="to('/wechat-user-info/up-view?v=Intro')" class="title">个性签名</p>
            <p  onclick="to('/wechat-user-info/up-view?v=Intro')" class="p1"><?=nl2br(empty($userInfo["intro"])?"待完善":$userInfo["intro"])?></p>
            <p class="title" onclick="to('/wechat-user-info/up-view?v=Info')" >个人简介</p>
            <p class="p1" onclick="to('/wechat-user-info/up-view?v=Info')" ><?=nl2br(empty($userInfo["info"])?"待完善":$userInfo["info"])?></p>
            <a href="/we-chat/logout" class="exitBtn">退出当前账号</a>
        </div>
    </div>
<script>
    function to(url)
    {
        window.location.href=url;
    }
</script>