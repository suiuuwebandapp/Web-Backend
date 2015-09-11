<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 下午2:35
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<link rel="stylesheet" type="text/css" href="/assets/pages/layout/top.css">
<link rel="icon" href="favicon.ico" />
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>

<?php
    $newMessageCount=0;
    $maxMessageCount=7;
?>
<?php if (isset($this->context->userObj)) { ?>
    <script type="text/javascript" src="/assets/chat/js/web_socket.js"></script>
    <script type="text/javascript" src="/assets/chat/js/json.js"></script>

    <!--header开始-->
    <div  class="header<?= isset($this->context->isIndex)?' indexHeader':''?><?=isset($this->context->isTripList)?' fixedBar':''?>" >
        <div class="header-main clearfix">
            <h2 class="logo fl"><a href="<?=Yii::$app->params['base_dir']; ?>"><img src="/assets/images/header/logo.png" width="106" height="43"></a></h2>
            <div class="search-out fl clearfix">
                <a href="javascript:;" class="search-btn fl"><img src="/assets/images/header/top-search.png" width="32" height="30"></a>
                <ul class="searchDrop" id="searchDrop"></ul>
                <input type="text" placeholder="想去哪里？" class="text fl" value="<?=isset($this->context->search)?$this->context->search:''?>" id="search">
            </div>
            <div class="header-right fr">
                <ul>
                    <li class="name" id="suiuu-btn2">
                        <a href="<?=\common\components\SiteUrl::getViewUserUrl($this->context->userObj->userSign)?>"><img src="<?= $this->context->userObj->headImg; ?>" alt="" class="user"></a>
                        <a href="javascript:;" class="colGreen aname"><?= $this->context->userObj->nickname; ?></a>
                        <div class="my-suiuu" style="display: none;">
                            <span class="jiao"></span>
                            <ul>
                                <?php if($this->context->userObj->isPublisher==true){?>
                                    <li class="bg1"><a href="/user-info?tab=tripManager">我的随游</a></li>
                                <?php } ?>
                                <li class="bg2"><a href="/user-info?tab=myOrderManager">我的订单</a></li>
                                <li class="bg3"><a href="/user-info?tab=userInfo">个人中心</a></li>
                                <li class="bg4"><a href="/login/logout">安全退出</a></li>
                            </ul>

                        </div>
                    </li>
                    <li class="xitong" id="suiuu-btn1">
                        <a href="javascript:;" class="my-message xitong" id="topNewMessageBox">
                            <span style="display: none" class="newTip" id="topNewMessageCount"></span></a>
                        <div class="xit-sz">
                            <span class="jiao"></span>
                            <ol>
                                <li class="sx active" id="userMessageLiBtn">私信</li>
                                <li class="xtxx" id="sysMessageLiBtn">系统消息</li>
                            </ol>

                            <ul id="unReadUserMessageList">
                                <?php $noUserMessage=false;$moreUserMessage=false; ?>
                                <?php if(!empty($this->context->unReadMessageList['userList'])){ ?>
                                    <?php foreach($this->context->unReadMessageList['userList']  as $key=> $messageInfo){ ?>
                                        <?php $newMessageCount++;if($key>$maxMessageCount){ continue; } ?>
                                        <?php
                                            $nickname=$messageInfo['nickname'];
                                            if(mb_strlen($nickname,"UTF-8")>5){
                                                $nickname=mb_substr($nickname,0,5,"UTF-8");
                                            }
                                        ?>
                                        <li class="message">
                                            <a style="width: 240px;height: 40px" href="/user-info?tab=myMessage">
                                                <img src="<?=$messageInfo['headImg']?>" /><span><?=$nickname?></span>
                                                <p>给您发了私信</p>
                                            </a>
                                        </li>
                                        <?php ?>
                                        <?php if($key==$maxMessageCount){$moreUserMessage=true;}?>
                                    <?php } ?>
                                <?php }else{ $noUserMessage=true;} ?>
                                <li id="noUserMessage" <?=$noUserMessage?'style="display:block"':'style="display:none"' ?>><p class="message_p_center">暂无私信消息</p></li>
                                <li id="moreUserMessage" <?=$moreUserMessage?'style="display:block"':'style="display:none"' ?>><a href="/user-info?tab=myMessage"><p class="message_p_center">...</p> </a></li>
                            </ul>
                            <ul id="unReadSystemMessageList" style="display: none">
                                <?php $noSysMessage=false;$moreSysMessage=false; ?>
                                <?php if(!empty($this->context->unReadMessageList['sysList'])){ ?>
                                    <?php foreach($this->context->unReadMessageList['sysList'] as $key=> $messageInfo){ ?>
                                        <?php $newMessageCount++;if($key>$maxMessageCount){ continue; } ?>
                                        <?php
                                            $c=$messageInfo['content'];
                                            if(mb_strlen($content,"UTF-8")>12){
                                                $c=mb_substr($c,0,12,"UTF-8");
                                            }
                                        ?>
                                        <li class="message" onclick="changeSystemMessageRead('<?=$messageInfo['messageId']?>','<?=$messageInfo['url']?>')">
                                            <p><?=$c?></p>
                                        </li>
                                        <?php if($key==$maxMessageCount){$moreSysMessage=true;} ?>
                                    <?php } ?>
                                <?php }else{ $noSysMessage=true; } ?>

                                <li id="noSysMessage" <?=$noSysMessage?'':'style="display:none"' ?>><p class="message_p_center">暂无系统消息</p></li>
                                <li id="moreSysMessage" <?=$moreSysMessage?'':'style="display:none"' ?>><a href="/user-info?tab=myMessage"><p class="message_p_center">...</p> </a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="pubsy"><a href="/index/trip-help" class="bgGreen">发布随游</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!--header结束-->
<?php } else { ?>
    <!--nav begin-->
        <div class="nav-out<?=isset($this->context->isIndex)?' indexNav':''?><?=isset($this->context->isTripList)?' fixedBar':''?>" >
        <div class="nav-content clearfix">
            <h2 class="logo fl"><a href="<?=Yii::$app->params['base_dir']; ?>"><img src="/assets/images/header/logo.png" width="106" height="43"></a></h2>
            <div class="search-out fl clearfix">
                <a href="#" class="search-btn fl"><img src="/assets/images/header/top-search.png" width="32" height="30"></a>
                <input type="text" placeholder="想去哪里？" class="text fl" value="<?=isset($this->context->search)?$this->context->search:''?>" id="search">
            </div>
            <div class="nav-right">
                <ol>
                    <li class="zhuces"><a href="javascript:;" id="zhuce">注册</a></li>
                    <li class="logins"><a href="javascript:;" id="denglu">登录</a></li>
                    <li class="pubsy"><a href="/index/trip-help" class="bgGreen">发布随游</a></li>
                </ol>
            </div>
        </div>
    </div>
 <!--nav end-->
<?php } ?>

<!--nav end-->


<!--[if gt IE 9]>
<script type="text/javascript" src="/assets/chat/js/swfobject.js"></script>
<script type="text/javascript">WEB_SOCKET_SWF_LOCATION = "/assets/chat/swf/WebSocketMain.swf";</script>
<![endif]-->

<script type="text/javascript">
    var isLogin="<?=isset($this->context->userObj)?1:0?>";
    var emailTime="<?= array_key_exists('emailTime',$this->params)?$this->params['emailTime']:0;?>";
    var searchList='<?=isset($this->context->searchList)?json_encode($this->context->searchList):'';?>';
    var topNewMessageCount=<?=empty($newMessageCount)?0:$newMessageCount;?>;
    var maxMessageCount=<?=$maxMessageCount?>;
    /*** scoket connec***/
    if (typeof console == "undefined") {
        this.console = {
            log: function (msg) {
            }
        };
    }
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list = {}, timeid, reconnect = false;
    var sessionId = '<?=session_id()?>';


    $(document).ready(function(){
        $("#search").keypress(function(e){
            if(e.keyCode==13){
                window.location.href=UrlManager.getTripSearchUrl($("#search").val());
            }
        });
    });

    $(document).bind("click",function(e){
        var target = $(e.target);
        if(!target.hasClass("colGreen")){
            if(target.closest(".my-suiuu").length == 0){
                $(".my-suiuu").hide();
            }
        }

        target = $(e.target);
        if(!target.hasClass("my-message")){
            if(target.closest(".xit-sz").length == 0){
                $(".xit-sz").hide();
            }
        }
    });
</script>



