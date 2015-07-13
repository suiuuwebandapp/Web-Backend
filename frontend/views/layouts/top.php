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
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<style type="text/css">

    #emailTime input{
        font-size: 14px !important;
    }
    /*input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #858585 inset;
    }*/
    #unReadSystemMessageList p{
        width: 240px;
    }
    #unReadSystemMessageList a{
        height: auto;
    }
    .select2-container .select2-choice{
        height: 40px !important;
    }
    .myLogins .select2-container .select2-choice>.select2-chosen{
        color: #858585;
    }
    .myLogins .select2-container .select2-choice>.select2-chosen:hover{
        color: #858585;
    }
    .myLogins .down .rows .agreen{
        font-size: 12px;
        margin: 0px;
    }
</style>
<?php if (isset($this->context->userObj)) { ?>
    <!--header开始-->
    <div class="header">
        <div class="header-main clearfix w1200">
            <h2 class="logo"><a href="<?=Yii::$app->params['base_dir']; ?>"><img width="120" height="42" src="/assets/images/nav-ico.png"></a></h2>

            <div class="header-nav fl">
                <ul class="menu_ul">
                    <li class="active"><a href="<?=Yii::$app->params['base_dir']; ?>">首页</a></li>
                    <li><a href="/view-trip/list">随游</a></li>
                    <li><a href="/destination/list">目的地</a></li>
                    <li><a href="/article">专栏</a></li>
                    <li><a href="/index/create-travel">发布随游</a></li>
                </ul>
            </div>
            <div class="header-right fr">
                <ul>
                    <li class="xitong" id="suiuu-btn1"><a href="javascript:;"></a>
                        <div class="xit-sz">
                            <span class="jiao"></span>
                            <ol>
                                <li class="sx active" id="userMessageLiBtn">私信</li>
                                <li class="xtxx" id="sysMessageLiBtn">系统消息</li>
                            </ol>
                            <ul id="unReadUserMessageList">
                            </ul>
                            <ul id="unReadSystemMessageList" style="display: none">
                            </ul>
                        </div>
                    </li>
                    <li class="name" id="suiuu-btn2">
                        <img src="<?= $this->context->userObj->headImg; ?>" alt="">
                        <a href="javascript:;"><?= $this->context->userObj->nickname; ?></a>
                        <img src="/assets/images/header-icon.png" alt="" width="14" height="7" class="w20">

                        <div class="my-suiuu">
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
                </ul>
                <div class="search-out"><p class="search"><input type="text" value="" class="text-xqy" id="search-ipt"></p><a href="javascript:;" class="search-btn"></a></div>
            </div>
        </div>
    </div>
    <!--header结束-->
<?php } else { ?>
    <!--nav begin-->
    <div class="nav-out">
        <div class="nav-content w1200 clearfix">
            <h2 class="logo"><a href="<?=Yii::$app->params['base_dir']; ?>"><img src="/assets/images/nav-ico.png" width="120" height="42"></a></h2>

            <div class="nav">
                <ul class="menu_ul">
                    <li class="active"><a href="<?=Yii::$app->params['base_dir']; ?>">首页</a></li>
                    <li><a href="/view-trip/list">随游</a></li>
                    <li><a href="/destination/list">目的地</a></li>
                    <li><a href="/article">专栏</a></li>
                    <li><a href="javascript:;" onclick="$('#denglu').click()">发布随游</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <ol>
                    <li class="zhuces"><a href="javascript:;" id="zhuce">注册</a>
                   </li>
                   <li class="logins"><a href="javascript:;" id="denglu">登录</a>
                 </li>
             </ol>
             <div class="search-out"><p class="search"><input type="text" value="" class="text-xqy" id="search-ipt"></p><a href="javascript:;" class="search-btn"></a></div>
         </div>
     </div>
 </div>
 <!--nav end-->

<?php } ?>

<!--nav end-->


<script type="text/javascript">
    var isLogin="<?=isset($this->context->userObj)?1:0?>";
    var emailTime="<?= array_key_exists('emailTime',$this->params)?$this->params['emailTime']:0;?>";
</script>
