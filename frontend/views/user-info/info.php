<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:19
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/imgAreaSelect/css/imgareaselect-default.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">

<style type="text/css">
    .queue{
        display: none;
    }
    #uploadifive-reImg{
        display: none;
    }
    .sycon .myInformation .past01 .wdzl .sel-pic{
        width: 300px;
        height: 300px;
        text-align: center;
        max-width: 300px;
        max-height: 300px;
    }
    .sycon .myInformation .past01 .wdzl .sel-pic .sect{
        height: 300px;
        line-height: 300px;
    }
    .p_photo1,.p_photo2,.p_photo3{
        float: left;
    }


    .datetimepicker .prev{
        background-image: url('/assets/images/day_left.png');
        width:20px;
        height: 20px;
        background-repeat: no-repeat;
        background-position: center;
        padding:0;
    }
    .datetimepicker .next{
        background-image: url('/assets/images/day_right.png');
        width:20px;
        height: 20px;
        background-repeat: no-repeat;
        background-position: center;
        padding:0;
    }
    .datetimepicker th ,td{
        padding: 3px 5px;
    }
    .datetimepicker table{
        margin-top: 10px;
    }

    .select2-container .select2-choice {
        background-color: #eee;
        border-radius: 0px;
        font-size: 14px;
        color: dimgray;
    }
    .select2-drop {
        font-size: 14px;
    }
    .select2-highlighted {
        background-color: #eee;
    }
    .select2-no-results {
        font-size: 14px;
        color: dimgray;
        text-align: center;
    }

    .sycon .myInformation .past01 .wdzl .wdzl-xx span{
        float: left;
        width: 100px;
    }
    .sycon .myInformation .past02 .wdzl .wdzl-xx span{
        float: left;
        width: 100px;
    }
    .form_tip{
        font-size: 14px;
        padding-left: 20px;
        color: red;
        display: inline-block !important;
        text-align: right !important;
        float: right !important;
        width: 200px !important;
    }
    .sycon .myInformation .past01 .wdzl .wdzl-xx .select2-arrow{
        width: 20px !important;
    }
    .sycon .myInformation .past01 .wdzl .wdzl-xx .select2-chosen{
        width: 300px;
    }
    .sycon .myInformation .past02 .wdzl .wdzl-xx .phone-select .select2-arrow{
        width: 20px !important;
    }
    .sycon .myInformation .past02 .wdzl input.phone{
        height: 39px !important;
        margin-left: 20px;
        width: 210px;
    }
    .validate{
        background-color:  #73b9ff !important;
    }

    .sycon .mySuiyou .orderList .rubbish{
        top: 5px;
        right: 5px;
    }

    .no_result{
        margin-top: 50px;
    }
    .shield_btn{
        font-size: 12px;
        padding-left:16px;
        cursor: pointer;
    }

    .p_chose_card_front {
        height: 210px;
        width: 350px;
        text-align: center;
        line-height: 210px;
    }
    .upload_tip {
        font-size: 12px;
        text-align: center;
    }

    .imgPic {
        cursor: pointer;
        height: 210px;
        width: 350px;
    }

    .showImg {
        height: 210px;
        width: 350px;
    }

    #uploadifive-fileCardFront {
        display: none;
    }
    .queue {
        display: none;
    }

    .sel-pic{
        background: none;
    }
    .sycon .myInformation .past02 .wdzl .card_upload{
        background: #ff7a4d !important;
        padding-left: 0px !important;
        color: white;
    }
</style>

<?php
    $idCardImg="";
    if(!empty($userPublisher)&&!empty($userPublisher->idCardImg)){
        $idCardImg=$userPublisher->idCardImg;
    }
?>
<input type="hidden" id="lon"/>
<input type="hidden" id="lat"/>

<!--------------header-end-------------->
<!------------banner----------------->
<div class="syTop">
    <div class="banner">
        <div class="banner-inner">
            <a href="javascript:;" class="btn">设置</a>
        </div>
    </div>
    <div class="user w1200">
        <a href="javascript:;" class="userPic"><img src="<?=$this->context->userObj->headImg ?>" width="120px" alt=""></a>
        <span><?=$this->context->userObj->nickname ?></span>
        <p><?=$this->context->userObj->intro ?></p>
        <ul>
            <li>性别:<b><?php if($this->context->userObj->sex==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($this->context->userObj->sex==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b></li>
            <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($this->context->userObj->birthday)?></b></li>
            <li>城市:<b>北京</b></li>
            <li>职业:<b><?=$this->context->userObj->profession;?></b></li>
        </ul>
    </div>
</div>

<!-------------个人中心---------------->
<div class="sycon w1200 clearfix">
    <!-----------con-nav-------------->
    <ul class="con-nav synav">
        <li><a href="javascript:;"  class="active" id="myMessage">收件箱</a></li>
        <li><a href="javascript:;" id="myComment">发言</a></li>
        <li><a href="javascript:;" id="myCollect">收藏</a></li>
        <li><a href="javascript:;" id="myOrderManager">我的预定</a></li>
        <li><a href="javascript:;" id="tripManager">随游管理</a></li>
        <li><a href="javascript:;" id="userInfo">个人资料</a></li>
    </ul>
    <!-------------TabCon1-我的邮件------------>
    <div class="tab-div myEmail TabCon clearfix" style="display:block">
        <ul class="emailNav recTit">
            <li><a href="javascript:;"  class="active">私信</a></li>
            <li><a href="javascript:;" id="userMessageSetting">设置</a></li>
        </ul>
        <div class="emailCon past01 slideRec" style="display:block;">
            <div class="left" id="messageSessionDiv">
                <ul>
                </ul>
                <div class="pages" style="display: none">
                    <ol>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>5</li>
                        <li>...</li>
                    </ol>
                </div>
            </div>
            <div class="right" id="messageInfoDiv">
                <div class="con">
                    <ul>
                    </ul>
                    <div class="write"><input type="text" class="txt" id="messageContent"><input type="button" class="btn" value="发送" id="sendMessageBtn"></div>
                </div>
            </div>
        </div>
        <div class="emailCon past03 slideRec">
            <div class="con clearfix">
                <p id="user_message_setting_title">被屏蔽用户</p>
                <div id="messageShieldList">
                </div>
                <h2>隐私设置</h2>
                <input type="radio" id="user_message_setting_all" name="user_message_setting_status"
                       value="<?=\common\entity\UserMessageSetting::USER_MESSAGE_SETTING_STATUS_ALLOW_ALL?>" style="opacity: 0;float: left">
                <label for="user_message_setting_all">所有人都可以给我发私信（不包括你屏蔽的用户）</label>
                <input type="radio" id="user_message_setting_none" name="user_message_setting_status"
                       value="<?=\common\entity\UserMessageSetting::USER_MESSAGE_SETTING_STATUS_REFUSE_ALL?>" style="opacity: 0;float: left">
                <label for="user_message_setting_none">不接受任何人的收信（选择此项后，您依然可以收到系统自动发送的通知私信）</label>
            </div>

        </div>
    </div>
    <!-------------TabCon2-发言------------->
    <div class="tab-div huifu TabCon clearfix">

        <div class="huifu-list">
            <ul id="commentList_51">
            </ul>
            <ol id="spage"></ol>
        </div>
    </div>
    <!-------------TabCon3-收藏------------->
    <div class="tab-div shoucang TabCon clearfix">
        <ul class="clearfix" id="myCollectList">
        </ul>
    </div>
    <!-------------TabCon4-我的预定------------->
    <div class="tab-div orderCon TabCon clearfix">
        <ul class="myOderNav innerNav">
            <li><a href="#"  class="active" id="unFinishOrderManager">未完成订单</a></li>
            <li><a href="#" id="finishOrderManager">过往订单</a></li>
        </ul>
        <div class="myOder nowOder innerCon" style="display:block;" id="unFinishList">
        </div>
        <div class="myOder pastOder innerCon" id="finishList">

        </div>
    </div>
    <!-------------TabCon5-随游管理------------->
    <div class="tab-div mySuiyou TabCon clearfix">
        <ul class="myOderNav tabTitle">
            <li><a href="javascript:;" class="active" id="unConfirmOrderManager">待接订单</a></li>
            <li><a href="javascript:;" id="myPublisherOrder">随游订单</a></li>
            <li><a href="javascript:;" id="myTripManager">我的随游</a></li>
            <li><a href="javascript:;" id="myJoinTripManager">我加入的随游</a></li>
        </ul>
        <div class="myOder past01 tabCon" style="display:block;" id="unConfirmList">
        </div>
        <div class="myOder past01 tabCon" id="myPublisherOrderList">
        </div>
        <div class="myOder past02 tabCon" id="myTripList">
        </div>
        <div class="myOder past03 tabCon" id="myJoinTripList">
        </div>


    </div>

    <!-------------TabCon6-个人资料------------>
    <div class="tab-div myInformation TabCon clearfix">
        <ul class="InformationNav myTit">
            <li><a href="#"  class="active">我的资料</a></li>
            <li><a href="#">身份验证</a></li>
            <li><a href="#">账号设置</a></li>
        </ul>
        <div class="InformationCon past01 myCon" style="display:block;">
            <form id='coordinates_form' method="post">
                <input type='hidden' id="img_x" name='x' class='x' value='0'/>
                <input type='hidden' id="img_y" name='y' class='y' value='0'/>
                <input type='hidden' id="img_w" name='w' class='w' value='0'/>
                <input type='hidden' id="img_h" name='h' class='h' value='0'/>
                <input type='hidden' id="img_rotate" name='rotate' class='rotate' value='0'/>
                <input type="hidden" id="img_src" name="src" value=""/>
            </form>

            <div class="wdzl clearfix">
                <div class="sel-pic" id="crop_container">
                    <input type="file" id="reImg" />
                    <img id="img_origin" style="display: none;max-height: 300px;max-width: 300px" border="0"/>
                    <input id="uploadBtn"  class="sect" type="button" value="点击上传照片"/>
                    <input id="uploadImgConfirm"  class="btn sure" type="button" value="确定"/>
                    <input id="uploadImgCancle" class="btn cancel" type="button" value="取消"/>
                </div>
                <div id="reQueue" class="queue"></div>
                <div class="wdzl-img clearfix">
                    <div class="p_photo1" style="width:122px;height:122px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px">
                        <img src="<?=$this->context->userObj->headImg ?>" alt="" width="122px" height="122px" style="border-radius:0px"/>
                    </div>
                    <div class="p_photo2"  style="width:66px;height:66px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px;margin-top: 20px;margin-left: 20px;">
                        <img src="<?=$this->context->userObj->headImg ?>" alt="" width="66px" height="66px" style="border-radius:0px">
                    </div>
                    <div class="p_photo3"  style="width:40px;height:40px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px;margin-top: 35px;margin-left: 20px;">
                        <img src="<?=$this->context->userObj->headImg ?>" alt="" width="40px" height="40px" style="border-radius:0px;">
                    </div>
                </div>
                <div class="radio">
                    <span>性别：</span>
                    <div class="sexs">
                        <form name="form1" method="post" action="">
                            <input type="radio" value="1" id="rad01" name="sex">
                            <label for="rad01">男</label>
                            <input type="radio" value="0" id="rad02" name="sex">
                            <label for="rad02">女</label>
                            <input type="radio" value="2" id="rad03" name="sex" >
                            <label for="rad03">保密</label>
                        </form>
                    </div>
                </div>
                <div class="wdzl-xx">
                    <p><span>昵称:</span><span class="form_tip" id="nicknameTip"></span></p>
                    <input type="text" id="nickname" value="<?=$this->context->userObj->nickname?>" class="wdzj-text">
                    <p><span>生日:</span><span class="form_tip" id="birthdayTip"></span></p>
                    <input type="text" value="<?=$this->context->userObj->birthday=='0000-00-00'?'1990-01-01':$this->context->userObj->birthday;?>" id="birthday" class="wdzj-text">
                    <p><span>个性签名:</span><span class="form_tip" id="introTip"></span></p>
                    <input type="text" id="intro" value="<?=$this->context->userObj->intro?>" class="wdzj-text">
                    <p><span>常住地:</span><span class="form_tip" id="cityTip"></span></p>
                    <div>
                        <select id="countryId" name="country" class="select2" required placeholder="国家">
                            <option value=""></option>
                            <?php foreach ($countryList as $c) { ?>
                                <option value="<?= $c['id'] ?>"
                                    <?php  if($c['id']==$this->context->userObj->countryId){echo "selected";} ?>
                                    >  <?= $c['cname'] . "/" . $c['ename'] ?></option>
                            <?php } ?>
                        </select>
                        <select id="cityId" name="city" class="select2" required placeholder="城市"></select>
                    </div>
                    <div class="map">
                        <iframe id="mapFrame" name="mapFrame" src="/google-map/to-map" width="350px" height="330px;" frameborder="0" scrolling="no"></iframe>
                    </div>
                    <p><span>职业:</span><span class="form_tip" id="nicknameTip"></span></p>
                    <div class="shenfen">
                        <input type="radio" value="持证导游" id="shenfen01" name="profession">
                        <label for="shenfen01">持证导游</label>
                        <input type="radio" value="业余导游" id="shenfen02" name="profession">
                        <label for="shenfen02">业余导游</label>
                        <input type="radio" value="学生" id="shenfen03" name="profession">
                        <label for="shenfen03">学生</label>
                        <input type="radio" value="旅游爱好者" id="shenfen04" name="profession">
                        <label for="shenfen04">旅游爱好者</label>
                        <input type="radio" value="其他" id="shenfen05" name="profession">
                        <label for="shenfen05">其他</label>
                        <input type="text" class="other" id="other">

                        <!----显示隐藏的其他输入框------>
                        <script type="text/javascript">
                            $(function(){
                                $('.wdzl .shenfen input').click(function(e) {
                                    if($('.wdzl .shenfen #shenfen05').prop("checked")){
                                        $('.wdzl .shenfen input.other').css('display','block')
                                    }else{
                                        $('.wdzl .shenfen input.other').css('display','none')
                                    }
                                });
                            });
                        </script>
                    </div>
                    <p><span>个人简介:</span><span class="form_tip" id="infoTip"></span></p>
                    <textarea class="textarea" id="info"><?=$this->context->userObj->info?></textarea>
                    <a href="javascript:;" id="updateInfoBtn"  class="surebtn">保存修改</a>
                </div>
            </div>
        </div>
        <div class="InformationCon past02 myCon">
            <div class="wdzl clearfix">
                <div class="wdzl-xx">
                    <p><span>手机号验证:</span><span id="phoneTip" class="form_tip"></span></p>
                    <div style="clear: both"></div>
                    <div class="phone-select">
                        <div class="sect">
                            <select id="codeId" name="countryIds" class="areaCodeSelect" required>
                                <option value=""></option>
                                <?php foreach ($countryList as $c) { ?>
                                    <?php if(empty($c['areaCode'])){continue;} ?>
                                    <?php if ($c['areaCode'] == $this->context->userObj->areaCode) { ?>
                                        <option selected
                                                value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                    <?php } ?>

                                <?php } ?>
                            </select>
                        </div>
                        <input id="phone" type="text" value="<?= $this->context->userObj->phone?>" class="phone fl" >
                    </div>
                    <p class="p1">
                        <input type="text" class="text fl"  maxlength="6" id="code_p">
                        <input type="button" value="获取验证码" class="btn fl" id="getCode">
                        <?php if($this->context->userObj->phone){?>
                        <input type="button" value="立即修改" class="btn validate" id="validatePhone">
                        <?php  }else{?>
                            <input type="button" value="立即验证" class="btn validate" id="validatePhone">
                        <?php  }?>
                    </p>
                    <p><span>邮箱验证:</span><span id="emailTip" class="form_tip"></span></p>
                    <p class="p1 p2">
                        <span class="fl">输入邮箱</span>
                        <input type="text" class="text fl" id="email_info" value="<?= $this->context->userObj->email?>">
                        <?php if($this->context->userObj->email){?>
                            <input type="button" value="修改" class="btn fl" id="validateEmail_info">
                        <?php  }else{?>
                            <input type="button" value="邮箱验证" class="btn fl" id="validateEmail_info">
                        <?php  }?>
                    </p>
                    <div style="clear: both"></div>
                    <p><span>实名认证</span><span id="cardTip" class="form_tip"></span></p>
                    <div style="clear: both"></div>
                    <div class="sel-pic">
                        <div id="divCardFront" class="imgPic">
                            <img src="<?=$idCardImg?>" id="imgFront" style="display: none" class="showImg"/>
                            <p class="p_chose_card_front">点击上传护照</p>
                        </div>
                        <input id="fileCardFront" type="file"/>
                        <div id="frontQueue" class="queue"></div>
                    </div>
                    <p class="upload_tip">上传文件大小不能大于1M,支持格式png、jpg、jpeg</p>
                    <br/>
                    <input type="button" value="上 传" class="btn sure card_upload" id="uploadAll">
                    <br/>
                    <div style="clear: both"></div>
                    <p><span>更多认证</span><span class="form_tip"></span></p>
                    <div class="moreRen">
                        <ul>
                            <li><b class="icon sina"></b><input class="active" type="button" value="关联"></li>
                            <li><b class="icon weixin"></b><input type="button" value="关联"></li>
                            <li><b class="icon qq"></b><input type="button" value="关联"></li>
                        </ul>
                    </div>
                    <div style="clear: both"></div>
                    <p style="display: none"><a href="#"  class="surebtn">保存修改</a></p>

                </div>
            </div>
        </div>
        <div class="InformationCon past03 myCon">
            <div class="wdzl clearfix">
                <div class="wdzl-xx">
                    <p class="Mtitle">密码设置</p>
                    <span>旧密码:</span>
                    <input type="password" id="oPassword_user_info">
                    <span>新密码:</span>
                    <input type="password" id="password_user_info">
                    <span>确认密码:</span>
                    <input type="password" id="qPassword_user_info">
                    <span></span>
                    <span></span>
                    <p class="Mtitle">收款设置</p>
                    <span></span>
                    <div class="moreRen">
                        <ul>
                            <li><b class="icon zfb"></b><input class="active" type="button" value="关联"></li>
                            <li><b class="icon weixin"></b><input type="button" value="关联"></li>
                            <li><b class="icon sina"></b><input type="button" value="关联"></li>
                        </ul>
                    </div>
                    <span></span>
                    <a href="javascript:;"  class="surebtn" id="password_update_info">保存修改</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-----------个人中心-end--------------->
<script type="text/javascript">
    var tripServiceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?>';
    var tripServiceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?>';
    var isPublisher=<?=$this->context->userObj->isPublisher?'true':'false';?>;

    var userProfession='<?=$this->context->userObj->profession?>';
    var userSex='<?=$this->context->userObj->sex?>';
    var cityId='<?=$this->context->userObj->cityId; ?>';
    var userHeadImg='<?=$this->context->userObj->headImg; ?>';
    var userSign='<?=$this->context->userObj->userSign; ?>';
    var phoneTime = 0;
    var phoneTimer;

</script>

<script type="text/javascript" src="/assets/js/myTab.js"></script>
<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.js" ></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" ></script>
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/pages/user-info/user-info.js"></script>

