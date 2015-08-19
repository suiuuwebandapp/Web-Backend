<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:19
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/plugins/imgAreaSelect/css/imgareaselect-default.css"/>
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-uploadifive/uploadifive.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/select2/select2_metro.css">
<link rel="stylesheet" type="text/css" href="/assets/pages/user-info/info.css">

<style type="text/css">

</style>


<input type="hidden" id="lon"/>
<input type="hidden" id="lat"/>

<!--------------header-end-------------->
<!------------banner----------------->

<!-------------个人中心---------------->
<div class="sycon w1200 clearfix">
    <!-----------con-nav-------------->
    <ul class="con-nav synav">
        <li><a href="javascript:;"  <?php if ($tab == "" || $tab == "myMessage") {echo "class='active'";}; ?> id="myMessage">收件箱</a></li>
        <li><a href="javascript:;" <?php if ($tab == "myComment") {echo "class='active'";}; ?> id="myComment">发言</a></li>
        <li><a href="javascript:;" <?php if ($tab == "myCollect") { echo "class='active'";}; ?> id="myCollect">收藏</a></li>
        <li><a href="javascript:;" <?php if ($tab == "myOrderManager") { echo "class='active'";}; ?> id="myOrderManager">我的预定</a></li>
        <?php if ($this->context->userObj->isPublisher) { ?>
            <li><a href="javascript:;" <?php if ($tab == "tripManager") {echo "class='active'";}; ?> id="tripManager">随游管理</a></li>
        <?php } ?>
        <li><a href="javascript:;" <?php if ($tab == "userInfo") { echo "class='active'";}; ?> id="userInfo">个人资料</a></li>
        <li><a href="javascript:;" <?php if ($tab == "userAccount") {echo "class='active'";}; ?> id="userAccount">个人账户</a></li>
    </ul>
    <!-------------TabCon1-我的邮件------------>
    <div class="tab-div myEmail TabCon clearfix" <?php if ($tab == "" || $tab == "myMessage") {
        echo "style='display:block'";
    } else {
        echo "style='display:none'";
    }; ?> >
        <ul class="emailNav recTit">
            <li><a href="javascript:;" class="active">私信</a></li>
            <li><a href="javascript:;" id="userMessageSetting">设置</a></li>
        </ul>
        <div class="emailCon past01 slideRec" style="display:block;">
            <div id="messageNothing" style="padding-top: 80px;margin-left:290px;" class="sycoNothing"><img src="/assets/images/N01.png" width="78" height="78"><p>您还没有消息哦</p></div>
            <div class="left" id="messageSessionDiv">
                <ul style="height: auto"></ul>
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
                    <ul></ul>
                    <div class="write" id="write_div">
                        <input type="text" class="txt" id="messageContent">
                        <input type="button" class="btn" value="发送" id="sendMessageBtn">
                    </div>
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
                       value="<?= \common\entity\UserMessageSetting::USER_MESSAGE_SETTING_STATUS_ALLOW_ALL ?>"
                       style="opacity: 0;float: left">
                <label for="user_message_setting_all">所有人都可以给我发私信（不包括你屏蔽的用户）</label>
                <input type="radio" id="user_message_setting_none" name="user_message_setting_status"
                       value="<?= \common\entity\UserMessageSetting::USER_MESSAGE_SETTING_STATUS_REFUSE_ALL ?>"
                       style="opacity: 0;float: left">
                <label for="user_message_setting_none">不接受任何人的收信（选择此项后，您依然可以收到系统自动发送的通知私信）</label>
            </div>

        </div>
    </div>

    <!-------------TabCon2-发言------------->
    <div class="tab-div huifu TabCon clearfix" <?=$tab == "myComment"?"style='display:block'":"style='display:none'"?> >
        <div class="huifu-list">
            <ul id="commentList_51"></ul>
            <ol id="spage"></ol>
        </div>
        <div id="commentNothing" style="padding-top: 0px" class="sycoNothing"><img src="/assets/images/N02.png" width="78" height="78"><p>您还没有发言哦</p></div>
    </div>

    <!-------------TabCon3-收藏------------->
    <div class="tab-div shoucang TabCon clearfix" <?=$tab == "myCollect"?"style='display:block'":"style='display:none'"?> >
        <ul class="clearfix" id="myCollectList">
        </ul>
        <div id="collectNothing" style="padding-top: 90px" class="sycoNothing"><img src="/assets/images/N03.png" width="78" height="78"><p>您还没有收藏哦</p></div>
    </div>

    <!-------------TabCon4-我的预定------------->
    <div class="tab-div orderCon TabCon clearfix" <?=$tab == "myOrderManager"?"style='display:block'":"style='display:none'"?> >
        <ul class="myOderNav innerNav">
            <li><a href="javascript:;" class="active" id="unFinishOrderManager">随游订单</a></li>
            <li><a href="javascript:;" id="finishOrderManager">过往订单</a></li>
        </ul>
        <div class="myOder nowOder innerCon" style="display:block;" id="unFinishList"></div>
        <div class="myOder pastOder innerCon" id="finishList"></div>
        <div id="myOrderNothing" style="padding-top: 90px;padding-bottom: 20px" class="sycoNothing"><img src="/assets/images/N04.png" width="78" height="78"><p>您还没有行程，去随游预定吧</p></div>
        <div class="advertising"><img src="/assets/images/ad.jpg" width="1201" height="401"></div>
    </div>

    <!-------------TabCon5-随游管理------------->
    <div class="tab-div mySuiyou TabCon clearfix" <?php if ($tab == "tripManager") {
        echo "style='display:block'";
    } else {
        echo "style='display:none'";
    }; ?>>
        <ul class="myOderNav tabTitle">
            <li><a href="javascript:;" class="active" id="unConfirmOrderManager">新订单</a></li>
            <li><a href="javascript:;" id="myPublisherOrder">随游订单</a></li>
            <li><a href="javascript:;" id="myTripManager">我的随游</a></li>
            <li><a href="javascript:;" id="myJoinTripManager">我加入的随游</a></li>
        </ul>
        <div class="myOder past01 tabCon" style="display:block;" id="unConfirmList">
        </div>
        <div class="myOder past01 tabCon" id="myPublisherOrderList">
        </div>
        <div class="myOder past03 tabCon" id="myTripList">
        </div>
        <div class="myOder past03 tabCon" id="myJoinTripList">
        </div>
        <div id="tripNothing" style="padding-top: 90px;" class="sycoNothing"><img src="/assets/images/N05.png" width="78" height="78"><p></p></div>

    </div>

    <!-------------TabCon6-个人资料------------>
    <div class="tab-div myInformation TabCon clearfix" <?php if ($tab == "userInfo") {
        echo "style='display:block'";
    } else {
        echo "style='display:none'";
    }; ?>>
        <ul class="InformationNav myTit">
            <li><a href="javascript:;"  id="myUserInfo" <?php if (empty($tabInfo)) {
                    echo "class='active'";
                }; ?>>我的资料</a></li>
            <li><a href="javascript:;">头像/相册</a></li>
            <li><a href="javascript:;">认证信息</a></li>
            <li><a href="javascript:;" id="userAccountLink" <?php if ($tabInfo == "userAccountLink") {
                    echo "class='active'";
                }; ?>>账号设置</a></li>
        </ul>
        <div class="InformationCon past01 myCon" <?php if (empty($tabInfo)) {
            echo "style='display:block'";
        }; ?>>

            <div class="box">
                <div class="wdzl clearfix">
                    <p class="title">基本信息</p>

                    <div class="wdzl-xx">
                        <p><span>姓名:</span><span class="form_tip" id="nameTip"></span></p>

                        <div class="line clearfix">
                            <input type="text" placeholder="姓" id="surname"
                                   value="<?= $this->context->userObj->surname; ?>">
                            <input type="text" placeholder="名" class="fr" id="name"
                                   value="<?= $this->context->userObj->name; ?>">
                        </div>
                        <div class="radio">
                            <p><span>性别：</span></p>

                            <div class="sexs" style="clear:both;">
                                <input type="radio" value="1" id="rad01" name="sex"><label for="rad01">男</label>
                                <input type="radio" value="0" id="rad02" name="sex"><label for="rad02">女</label>
                                <input type="radio" value="2" id="rad03" name="sex"><label for="rad03">保密</label>
                            </div>
                        </div>
                        <p><span>昵称:</span><span class="form_tip" id="nicknameTip"></span></p>
                        <input type="text" id="nickname" value="<?= $this->context->userObj->nickname ?>"
                               placeholder="4到14个字符">

                        <p><span>出生日期:</span><span class="form_tip" id="birthdayTip"></span></p>
                        <input type="text"
                               value="<?= $this->context->userObj->birthday == '0000-00-00' ? '1990-01-01' : $this->context->userObj->birthday; ?>"
                               id="birthday" class="wdzj-text">

                        <p><span>常住地:</span><span class="form_tip" id="cityTip"></span></p>

                        <div>
                            <select id="countryId" name="country" class="select2" required placeholder="国家">
                                <option value=""></option>
                                <?php foreach ($countryList as $c) { ?>
                                    <option value="<?= $c['id'] ?>"
                                        <?php if ($c['id'] == $this->context->userObj->countryId) {
                                            echo "selected";
                                        } ?>
                                        >  <?= $c['cname'] ?></option>
                                <?php } ?>
                            </select>
                            <select id="cityId" name="city" class="select2" required placeholder="城市"></select>
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
                                $(function () {
                                    $('.wdzl .shenfen input').click(function (e) {
                                        if ($('.wdzl .shenfen #shenfen05').prop("checked")) {
                                            $('.wdzl .shenfen input.other').css('display', 'block')
                                        } else {
                                            $('.wdzl .shenfen input.other').css('display', 'none')
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <p><span>个性签名:</span><span class="form_tip" id="introTip"></span></p>
                        <input type="text" id="intro" value="<?= $this->context->userObj->intro ?>" class="wdzj-text">

                        <p><span>自我介绍:</span><span class="form_tip" id="infoTip"></span></p>
                        <textarea class="textarea" id="info"><?= $this->context->userObj->info ?></textarea>

                        <p class="colGrey">你的性格爱好是什么？有什么值得炫耀的经历？你最喜爱的旅行目的地是哪里？在旅行中你有什么独特的际遇？</p>

                        <p class="colGrey">分享你的故事，帮助别人更好地认识你！</p>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="wdzl">
                    <p class="title">联系方式</p>

                    <p><span>手机</span><span id="phoneTip" class="form_tip"></span></p>

                    <div class="tog clearfix" style="clear: both">
                        <input type="text" placeholder="您还未添加电话联系方式" readonly
                               value="<?= $this->context->userObj->phone; ?>" id="phone_val">
                        <?php if ($this->context->userObj->phone) { ?>
                            <a href="javascript:;" class="btn adds" id="phone_show_btn">修改</a>
                        <?php } else { ?>
                            <a href="javascript:;" class="btn adds" id="phone_show_btn">添加</a>
                        <?php } ?>
                        <p class="colGrey">仅当您和另一名随游用户确认预订时，此资料才会被分享。这是我们帮助大家联系彼此的方式</p>

                        <div class="togT">
                            <div class="line clearfix">
                                <select id="codeId" name="countryIds" class="areaCodeSelect" required>
                                    <option value=""></option>
                                    <?php foreach ($countryList as $c) { ?>
                                        <?php if (empty($c['areaCode'])) {
                                            continue;
                                        } ?>
                                        <?php if ($c['areaCode'] == $this->context->userObj->areaCode) { ?>
                                            <option selected
                                                    value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                        <?php } else { ?>
                                            <option
                                                value="<?= $c['areaCode'] ?>"><?= $c['cname'] . " " . $c['areaCode'] ?></option>
                                        <?php } ?>

                                    <?php } ?>
                                </select>
                                <input type="text" class="phN" id="phone">
                            </div>
                            <div class="code clearfix">
                                <input type="text" class="text fl" id="code_p" maxlength="6">
                                <a href="javascript:;" class="btn2 fl" id="getCode">获取验证码</a>
                            </div>
                            <a href="javascript:;" class="btn3" id="validatePhone">立即验证</a>
                        </div>
                    </div>
                    <p><span>邮箱</span><span id="emailTip" class="form_tip"></span></p>

                    <div class="tog clearfix" style="clear: both">
                        <input type="text" value="<?= $this->context->userObj->email ?>" id="email_info">
                        <?php if ($this->context->userObj->email) { ?>
                            <a href="javascript:;" class="btn" id="validateEmail_info">修改</a>
                        <?php } else { ?>
                            <a href="javascript:;" class="btn" id="validateEmail_info">绑定</a>
                        <?php } ?>
                        <p class="colGrey">作为找回密码的方式，我们不会向其他用户透漏您的电子邮箱</p>
                    </div>
                    <span>QQ</span>
                    <input type="text" id="qq" value="<?= $this->context->userObj->qq; ?>">

                    <p class="colGrey">作为紧急联络方式，我们不会向其他用户透露您的QQ</p>
                    <span>微信</span>
                    <input type="text" id="wx" value="<?= $this->context->userObj->wechat; ?>">

                    <p class="colGrey">作为紧急联络方式，我们不会向其他用户透露您的微信</p>
                </div>
            </div>
            <a href="javascript:;" id="updateInfoBtn" class="btn4">保存修改</a>
        </div>
        <div class="InformationCon past011 myCon">
            <div class="box">
                <div class="wdzl clearfix">
                    <form id='coordinates_form' method="post">
                        <input type='hidden' id="img_x" name='x' class='x' value='0'/>
                        <input type='hidden' id="img_y" name='y' class='y' value='0'/>
                        <input type='hidden' id="img_w" name='w' class='w' value='0'/>
                        <input type='hidden' id="img_h" name='h' class='h' value='0'/>
                        <input type='hidden' id="img_rotate" name='rotate' class='rotate' value='0'/>
                        <input type="hidden" id="img_src" name="src" value=""/>
                    </form>
                    <p class="title">联系方式</p>

                    <p class="colGrey">清楚的正面照对用户了解彼此起着重大作用。通过一张风景照或者卡通形象认识一个人可不靠谱！因此请上传一张能清楚看到您脸部的照片。</p>

                    <div class="sel-pic" id="crop_container">
                        <input type="file" id="reImg"/>
                        <img id="img_origin" style="display: none;max-height: 210px;max-width: 350px" border="0"/>
                        <input id="uploadBtn" class="sect" type="button" value="点击上传照片"/>
                        <input id="uploadImgConfirm" class="btn sure" type="button" value="确定"/>
                        <input id="uploadImgCancle" class="btn cancel" type="button" value="取消"/>
                    </div>
                    <div id="reQueue" class="queue"></div>
                    <div class="wdzl-img clearfix">
                        <div class="p_photo1"
                             style="width:122px;height:122px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px">
                            <img src="<?= $this->context->userObj->headImg ?>" alt="" width="122px" height="122px"
                                 style="border-radius:0px"/>
                        </div>
                        <div class="p_photo2"
                             style="width:66px;height:66px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px;margin-top: 20px;margin-left: 20px;">
                            <img src="<?= $this->context->userObj->headImg ?>" alt="" width="66px" height="66px"
                                 style="border-radius:0px">
                        </div>
                        <div class="p_photo3"
                             style="width:40px;height:40px;overflow:hidden;text-align: center;overflow: hidden;margin: auto;border-radius:360px;margin-top: 35px;margin-left: 20px;">
                            <img src="<?= $this->context->userObj->headImg ?>" alt="" width="40px" height="40px"
                                 style="border-radius:0px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <p class="title">我的相册</p>
                <p class="colGrey">添加自己在旅行过程中的照片，让游客相信你是真正的旅行达人！</p>
                <ul class="list clearfix" id="user_photo_list">
                    <?php if(!empty($photoList)){ ?>
                        <?php foreach($photoList as $photo){ ?>
                            <li>
                                <a href="javascript:;" class="imgs" pic="" photoId="<?=$photo['photoId']?>">
                                    <span class="delet" onclick="removeUserPhoto(this)"></span>
                                    <img src="<?=$photo['url']?>">
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    <li><a id="userPhotoUpload" class="imgs"><img src="/assets/images/addPic.gif"></a></li>
                </ul>
                <div>
                    <input type="file" id="userPhotoFile" style="display: none"/>
                    <div id="photoQueue" class="queue"></div>
                </div>
            </div>
        </div>
        <div class="InformationCon past02 myCon">

            <?php
            $authPhone = false;
            $authEmail = false;
            $authUser = false;
            $authExperience = false;


            if (!empty($this->context->userObj->phone)) {
                $authPhone = true;
            }
            if (!empty($this->context->userObj->email)) {
                $authEmail = true;
            }
            if(!empty($userCard)&&$userCard->status==\common\entity\UserCard::USER_CARD_STATUS_SUCCESS){
                $authUser=true;
            }
            if(!empty($userAptitude)&&$userAptitude->status==\common\entity\UserAptitude::USER_APTITUDE_STATUS_SUCCESS){
                $authExperience = true;

            }
            ?>
            <?php if ($authPhone || $authEmail || $authUser || $authExperience) { ?>
                <div class="box">
                    <p class="title">已认证的选项</p>
                    <ul class="ul01">
                        <?php if ($authPhone) { ?>
                            <li>
                                <p class="p1">电话号码</p>

                                <p class="p2">您的电话
                            <span>
                                <?php $this->context->userObj->areaCode; ?>
                                <?php
                                $phone = $this->context->userObj->phone;
                                $pLength = strlen($phone);
                                if ($pLength % 2 == 0) {
                                    $subLength = ceil(($pLength - 4) / 2);
                                } else {
                                    $subLength = ceil(($pLength - 4) / 2) - 1;
                                }
                                echo substr($phone, 0, $subLength) . "XXXX" . substr($phone, $subLength + 4, $pLength);
                                ?>
                            </span>
                                    已经通过验证，该信息仅在订单确认之后会对双方公开。</p>
                            </li>
                        <?php } ?>
                        <?php if ($authEmail) { ?>
                            <li>
                                <p class="p1">电子邮箱地址</p>

                                <p class="p2">您已经确认了电子邮箱 <span><?php $this->context->userObj->email; ?></span>,
                                    这是我们与您沟通的重要途径。</p>
                            </li>
                        <?php } ?>
                        <?php if ($authUser) { ?>
                            <li>
                                <p class="p1">实名身份认证</p>

                                <p class="p2">您已经通过实名身份验证，我们会对该信息保密。</p>
                            </li>
                        <?php } ?>
                        <?php if ($authExperience) { ?>
                            <li>
                                <p class="p1">经历资质认证</p>

                                <p class="p2">您已经通过随游的官方旅行经历与资质认证，感谢您的支持与配合。</p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if (!$authPhone || !$authEmail || !$authUser || !$authExperience) { ?>
                <div class="box">
                    <p class="title">更多认证选项</p>
                    <ul class="ul02">
                        <?php if (!$authEmail) { ?>
                        <li>
                            <p class="p1">电子邮箱地址</p>

                            <p class="p2">您电子邮箱是我们与您保持沟通的重要渠道。<a href="javascript:;" onclick="toValidateEmail()">现在就去验证邮箱</a></p>
                        </li>
                        <?php } ?>
                        <?php if (!$authPhone) { ?>
                        <li>
                            <p class="p1">电话号码</p>

                            <p class="p2">电话号码是帮助大家互相联系的主要方式。<a href="javascript:;" onclick="toValidatePhone()">现在去添加联络信息。</a></p>
                        </li>
                        <?php } ?>
                        <?php if(!$authUser){ ?>
                            <?php
                                $idCardImg="";
                                $idCardStatus="";
                                if(!empty($userCard)){
                                    $idCardImg=$userCard->img;
                                    $idCardStatus=$userCard->status;
                                }
                            ?>
                        <li>
                            <p class="p1">实名身份验证<span id="cardTip" class="form_tip"></span></p>

                            <div class="pic">
                                <div class="sel-pic">
                                    <div id="divCardFront" class="imgPic">
                                        <?php if($idCardStatus!=""&&$idCardStatus==\common\entity\UserCard::USER_CARD_STATUS_WAIT){ ?>
                                            <img src="<?= $idCardImg ?>" class="showImg" />
                                        <?php }else{ ?>
                                            <img src="<?= $idCardImg ?>" id="imgFront" style="display: none" class="showImg" />
                                        <?php } ?>
                                        <p class="p_chose_card_front" style="background: #ddd">点击上传护照</p>
                                    </div>
                                    <input id="fileCardFront" type="file"/>
                                    <div id="frontQueue" class="queue"></div>
                                </div>
                                <?php if($idCardStatus!=""&&$idCardStatus==0){ ?>
                                    <a href="javascript:;" class="colOrange"  style="background: gainsboro;color: #ffffff">等待审核</a>
                                <?php }else{?>
                                    <a href="javascript:;" class="colOrange" id="resetUploadFront">重新上传</a>
                                    <a href="javascript:;" class="colGreen" id="uploadAll">立即验证</a>
                                <?php } ?>

                                <a class="upload_front_process"></a>
                            </div>
                            <p class="upload_tip">上传文件大小不能大于1M,支持格式png、jpg、jpeg</p>

                            <p>实名身份验证帮助彼此减少旅行中的风险，我们会对您的验证信息进行保密</p>
                        </li>
                        <?php } ?>
                        <?php if(!$authExperience){ ?>
                            <li>
                                <p class="p1">经历资质认证</p>
                                <p>您的旅行经历和旅行故事是让我们和其他用户认识您最有效的手段。 现在就与我们分享您的旅行故事吧！</p>
                                <p>
                                    申请经历资质认证后，我们会通过电子邮件或电话的方式与您取得联系进行认证，通过认证流程后，您会获得认证徽章，同时在征得您的同意后，我们也可能会把您的旅行故事拍摄成微电影，与更多热爱旅行的人分享您的旅行故事。</p>
                                <?php if(!empty($userAptitude)&&$userAptitude->status==\common\entity\UserAptitude::USER_APTITUDE_STATUS_WAIT){ ?>
                                    <a href="javascript:;" class="bgGreen btn5" style="background:gainsboro;">工作人员审核中</a>
                                <?php }else { ?>
                                    <a href="javascript:;" class="bgGreen btn5" id="applyUserAptitudeBtn">现在申请资质认证</a>
                                <?php } ?>


                            </li>
                        <?php } ?>

                    </ul>

                </div>
            <?php } ?>
        </div>
        <div class="InformationCon past03 myCon" <?php if ($tabInfo == "userAccountLink") {
            echo "style='display:block'";
        }; ?>>
            <div class="wdzl clearfix">
                <div class="wdzl-xx">
                    <p class="Mtitle">密码设置</p>
                    <span>旧密码:</span>
                    <input type="password" id="oPassword_user_info">
                    <span>新密码:</span>
                    <input type="password" id="password_user_info">
                    <span>确认密码:</span>
                    <input type="password" id="qPassword_user_info">
                    <a href="javascript:;" class="surebtn" id="password_update_info">保存修改</a>
                    <?php if ($this->context->userObj->isPublisher) { ?>
                        <hr/>
                        <p class="Mtitle">收款设置</p>
                        <div class="moreRen">
                            <ul id="bindAccountUl">
                                <?php if ($bindAlipayAccount) { ?>
                                    <li><b class="icon zfb"></b><input type="button" value="已关联"
                                                                       onclick="showBindAlipay()"></li>
                                <?php } else { ?>
                                    <li><b class="icon zfb"></b><input class="active" type="button" value="关联"
                                                                       id="showBindAlipayBtn"
                                                                       onclick="showBindAlipay()"></li>
                                <?php } ?>
                                <?php if ($bindWechatAccount) { ?>
                                    <li><b class="icon weixin"></b><input type="button" value="已关联"
                                                                          onclick="showChangeWechatDiv()"></li>
                                <?php } else { ?>
                                    <li><b class="icon weixin"></b><input class="active" type="button" value="关联"
                                                                          id="showBindWechatBtn"
                                                                          onclick="showWechatImgDiv()"></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-------------TabCon7-个人账户------------->
    <div class="tab-div accountCon TabCon clearfix" <?php if ($tab == "userAccount") {
        echo "style='display:block'";
    } else {
        echo "style='display:none'";
    }; ?>>
        <ul class="myOderNav actNav">
            <li><a href="javascript:;" class="active" id="accountList">账户信息</a></li>
            <li><a href="javascript:;" id="accountHistory">历史交易</a></li>
        </ul>
        <div id="accountDiv" class="myOder nowOder actCon" style="display:block;">
            <div class="top clearfix">
                <p class="row clearfix">账户余额：<span class="orange"
                                                   style="float: none">￥<?= intval($this->context->userObj->balance); ?></span>
                    <a href="javascript:;" id="toAddUserAccount" class=" btn blueColor">添加收款方式</a></p>
                <?php if (!empty($userAccountList)) { ?>
                    <div class="row clearfix">
                        <span class="accountSpan">选择收款方式：</span>

                        <div class="selets"><select name="" id="accountId">
                                <option value="">收款方式</option>
                                <?php foreach ($userAccountList as $userAccount) { ?>
                                    <?php if ($userAccount['type'] == \common\entity\UserAccount::USER_ACCOUNT_TYPE_ALIPAY) { ?>
                                        <option value="<?= $userAccount['accountId'] ?>">
                                            支付宝（<?= $userAccount['username'] . ":" . $userAccount['account'] ?>）
                                        </option>
                                    <?php } else if ($userAccount['type'] == \common\entity\UserAccount::USER_ACCOUNT_TYPE_WECHAT) { ?>
                                        <option value="<?= $userAccount['accountId'] ?>">
                                            微信（<?= $userAccount['username'] ?>）
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select></div>
                        <span class="accountSpan">输入金额：</span><input type="text" id="drawMoney"><a
                            href="javascript:drawMoney();" class="btnBlue">转出</a>
                    </div>
                <?php } ?>
            </div>
            <p class="listTit">账户清单</p>

            <div class="orderList clearfix">
                <dl class="order clearfix" id="accountDl">
                    <dt class="title">
                        <span>日期</span><span>类别</span><span>详情</span><span>金额</span>
                    </dt>
                </dl>
            </div>
        </div>
        <div id="accountHistoryDiv" class="myOder nowOder actCon">
            <div class="top clearfix">
                <div class="row clearfix" id="accountSearch">
                    <div class="selets">
                        <select id="accountYear">
                            <?php for ($i = 2015; $i <= date('Y'); $i++) {
                                if ($i == date('Y')) {
                                    echo '<option value="' . $i . '" selected>' . $i . '年</option>';
                                } else {
                                    echo '<option value="' . $i . '">' . $i . '年</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="selets">
                        <select id="accountMonth">
                            <?php for ($i = 1; $i <= 12; $i++) {
                                if ($i == date('m')) {
                                    echo '<option value="' . $i . '" selected>' . $i . '月</option>';
                                } else {
                                    echo '<option value="' . $i . '">' . $i . '月</option>';
                                }

                            } ?>
                        </select>
                    </div>
                    <div class="selets">
                        <select name="" id="accountType">
                            <option value="">全部</option>
                            <option value="1">随游服务收入</option>
                            <option value="2">路线分成服务</option>
                            <option value="3">转出</option>
                            <option value="4">其他收入</option>
                        </select>
                    </div>

                </div>

            </div>
            <p class="listTit">交易清单</p>

            <div class="orderList clearfix">
                <dl class="order clearfix" id="historyDl">
                    <dt class="title">
                        <span>日期</span><span>类别</span><span>详情</span><span>金额</span><span>状态</span>
                    </dt>
                </dl>
            </div>
        </div>


    </div>
</div>

<?php
$alipayAccount = null;
$wechatAccount = null;
?>
<?php if (!empty($userAccountList)) { ?>
    <?php foreach ($userAccountList as $userAccount) { ?>
        <?php if ($userAccount['type'] == \common\entity\UserAccount::USER_ACCOUNT_TYPE_ALIPAY) {
            $alipayAccount = $userAccount;
        } else if ($userAccount['type'] == \common\entity\UserAccount::USER_ACCOUNT_TYPE_WECHAT) {
            $wechatAccount = $userAccount;
        } ?>
    <?php } ?>
<?php } ?>

<!-----------支付宝绑定弹层--------------->
<div class="zfbPro screens" id="showAlipayDiv" style="z-index: 1001">
    <div class="top clearfix">
        <p class="bind_account_title">绑定支付宝账号</p>
    </div>

    <input type="text" id="bindAlipayAccount" placeholder="支付宝账号"
           value="<?= $alipayAccount == null ? "" : $alipayAccount['account'] ?>" maxlength="50">
    <input type="text" id="bindAlipayName" placeholder="真实姓名"
           value="<?= $alipayAccount == null ? "" : $alipayAccount['username'] ?>" maxlength="50">

    <p class="tip">此账号将是您的支付宝收款账号，请慎重填写！</p>
    <a href="javascript:;" onclick="bindAlipayAccount();"
       class="btn"><?= $alipayAccount == null ? "绑定账号" : "重新绑定支付宝账号" ?></a>
</div>

<div class="zfbPro screens" id="showWechatImgDiv" style="height: auto;text-align: center;z-index: 1001">
    <div id="login_container"></div>
</div>

<?php
$wechatNickname = "";
if (!empty($wechatAccount)) {
    $wechatNickname = $wechatAccount['username'];
}
?>

<div class="zfbPro screens" id="showWechatDiv" style="z-index: 1001">
    <div class="top clearfix">
        <p class="bind_account_title">绑定微信账号</p>
    </div>
    <input type="text" value="<?= $wechatNickname ?>" placeholder="微信昵称" readonly style="background-color: #ddd">
    <input type="text" value="" id="bindWechatName" placeholder="真实姓名" maxlength="50">

    <p class="tip">此账号将是您的微信收款账号，请慎重填写！</p>
    <a href="javascript:;" onclick="bindWechatAccount();" class="btn">绑定账号</a>
</div>
<div class="zfbPro screens" id="showChangeWechatDiv" style="height: 250px;z-index: 1001">
    <div class="top clearfix">
        <p class="bind_account_title">绑定微信账号</p>
    </div>
    <p style="text-align: center">已经绑定微信账号：<?= $wechatNickname ?></p>

    <p class="tip"></p>
    <a href="javascript:;" onclick="showWechatImgDiv();" class="btn">重新绑定微信账号</a>
</div>
<!-----------个人中心-end--------------->
<script type="text/javascript">
    var tripServiceTypeCount = '<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?>';
    var tripServiceTypePeople = '<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?>';
    var isPublisher =<?=$this->context->userObj->isPublisher?'true':'false';?>;

    var userProfession = '<?=$this->context->userObj->profession?>';
    var userSex = '<?=$this->context->userObj->sex?>';
    var cityId = '<?=$this->context->userObj->cityId; ?>';
    var userHeadImg = '<?=$this->context->userObj->headImg; ?>';
    var userSign = '<?=$this->context->userObj->userSign; ?>';
    var phoneTime = 0;
    var phoneTimer;
    var bindWechat = <?=$bindWechat;?>;
    var nowDate='<?=date('Y-m-d',time()); ?>';

</script>

<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js" ></script>
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/pages/user-info/info.js"></script>


<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>


<script>
    $(document).ready(function () {
        var obj = new WxLogin({
            id: "login_container",
            appid: "wxa33b47d647d7b8f6",
            scope: "snsapi_login",
            redirect_uri: "<?=Yii::$app->params['base_dir']."/user-account/get-wechat-info" ?>",
            state: "",
            style: "",
            href: ".loginPanel .title{display:none}"
        });
    });

    window._bd_share_config = {
        common : {
            bdText : '随游网-<?=htmlspecialchars(str_replace("\n"," ",111))?>',
            bdDesc : '随游网-<?=htmlspecialchars(str_replace("\n"," ",222))?>',
            bdUrl : '<?=Yii::$app->params['base_dir'].'/view-trip/info?trip=333';?>&',
            bdPic : '<?=444?>'
        },
        share : [{
            "bdSize" : 16
        }]
    }
    //以下为js加载部分
    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>
