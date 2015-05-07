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

<style>
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
</style>
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
        <a href="#" class="userPic"><img src="<?=$this->context->userObj->headImg ?>" width="120px" alt=""></a>
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
        <li><a href="#"  class="active">收件箱</a></li>
        <li><a href="#">发言</a></li>
        <li><a href="#">收藏</a></li>
        <li><a href="#" id="myOrderManager">我的预定</a></li>
        <li><a href="#" id="tripManager">随游管理</a></li>
        <li><a href="#" id="userInfo">个人资料</a></li>
    </ul>
    <!-------------TabCon1-我的邮件------------>
    <div class="tab-div myEmail TabCon clearfix" style="display:block">
        <ul class="emailNav recTit">
            <li><a href="#"  class="active">私信</a></li>
            <li><a href="#">写信</a></li>
            <li><a href="#">设置</a></li>
        </ul>
        <div class="emailCon past01 slideRec" style="display:block;">
            <div class="left">
                <ul>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                    <li>
                        <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                        <p class="words">日本京都有什么旅游景点</p>
                        <b class="datas">15：30</b>
                    </li>
                </ul>
                <div class="pages">
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
            <div class="right">
                <div class="con">
                    <ul>
                        <li class="zuo clearfix">
                            <img src="/assets/images/1.png">
                            <p>示聊天天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内</p>
                        </li>
                        <li class="you clearfix">
                            <img src="/assets/images/1.png">
                            <p>示聊天内容此处处显示聊天内容天内</p>
                        </li>
                        <li class="zuo clearfix">
                            <img src="/assets/images/1.png">
                            <p>示聊天天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此处显示聊天内容此内容此此处显示聊天内容此处显示聊天内容此处显示聊天内</p>
                        </li>
                    </ul>
                    <div class="write"><input type="text" class="txt"><input type="button" class="btn" value="发送"></div>
                </div>
            </div>
        </div>
        <div class="emailCon past02 slideRec">
            <div class="con">
                <p>收件人</p>
                <input type="text" placeholder="可同时给5个人发送私信，用户名之间用分号隔开">
                <textarea></textarea>
                <p>验证码</p>
                <div class="yanzheng">
                    <input type="text" class="text1"><img src="/assets/images/code.png" class="codePic">
                    <input type="button" value="换一个" class="change">
                    <input type="button" value="发送" class="btn">
                </div>

            </div>

        </div>
        <div class="emailCon past03 slideRec">
            <div class="con clearfix">

                <h2>屏蔽设置</h2>
                <div class="sets">
                    <input type="text" class="text1" placeholder="输入要屏蔽人的用户名/昵称">
                    <input type="button" value="屏蔽" class="btn">
                </div>
                <p>被屏蔽用户</p>
                <div class="setd">
                    <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                    <input type="button" value="取消屏蔽" class="btn">
                </div>
                <div class="setd">
                    <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                    <input type="button" value="取消屏蔽" class="btn">
                </div>
                <div class="setd">
                    <div class="people"><img src="/assets/images/1.png"><span>dengling</span></div>
                    <input type="button" value="取消屏蔽" class="btn">
                </div>
                <h2>隐私设置</h2>
                <input type="radio" id="all" name="s">
                <label for="all">所有人都可以给我发私信（不包括你屏蔽的用户）</label>
                <input type="radio" id="None" name="s">
                <label for="None">不接受任何人的收信（选择此项后，您依然可以收到系统自动发送的通知私信）</label>
            </div>

        </div>
    </div>
    <!-------------TabCon2-发言------------->
    <div class="tab-div huifu TabCon clearfix">
        <div class="huifu-top">
            <img src="" alt="" class="pic">
            <div class="picright">
                <h4>京都奈良公园一日游</h4>
                <p class="xing"><img alt="" src="/assets/images/start2.fw.png"><img alt="" src="/assets/images/start2.fw.png"><img alt="" src="/assets/images/start2.fw.png"><img alt="" src="/assets/images/start1.fw.png"><img alt="" src="/assets/images/start1.fw.png"></p>
            </div>
        </div>
        <div class="huifu-list">
            <ul>
                <li>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <span>回复</span>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <p>日本都有什么旅游景点？</p>
                    <b>13:50</b>
                </li>
                <li>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <span>回复</span>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <p>日本都有什么旅游景点？</p>
                    <b>13:50</b>
                </li>
                <li>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <span>回复</span>
                    <div class="userPic">
                        <a href="#"><img alt="" src="/assets/images/1.png"></a>
                        <span>xiaoleho</span>
                    </div>
                    <p>日本都有什么旅游景点？</p>
                    <b>13:50</b>
                </li>
            </ul>
        </div>
    </div>

    <!-------------TabCon3-收藏------------->

    <div class="tab-div shoucang TabCon clearfix">
        <ul class="clearfix">
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
            <li>
                <a href="javascript:;"><img src="/assets/images/grsc1.fw.png" alt=""></a>
                <div class="userPic">
                    <a href="#"><img src="/assets/images/1.png" alt=""></a>
                    <span>xiaoleho</span>
                </div>
                <p>日本京都奈良公园一日游</p>
            </li>
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
            <div class="orderList clearfix">
                <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish">
                <dl class="order clearfix">
                    <dt class="title">
                        <span>3小时前</span><span>随游</span><span>开始时间</span><span>随友</span><span>随友电话</span><span>出行日期</span><span>人数</span><span>单项服务</span>
                    </dt>
                    <dd>
                        <span class="pic"><img src="/assets/images/2.png"></span>
                        <span>日本京都奈良公园一日游秀公园...</span>
                        <span>9:00AM</span>
                        <span><a href="#" class="user"><img src="/assets/images/1.png" ></a><a href="#" class="message"><b>xiao</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>
                        <span>567890053</span>
                        <span>2015.3.14</span>
                        <span>2</span>
                        <span>接机<b>300</b><br>租车<b>500</b></span>
                    </dd>
                </dl>
                <p><a href="#" class="cancel">评论</a><a href="#" class="sure">分享</a>
                    <span>总价：<b>8000</b></span><span class="blue">已确认</span></p>
            </div>

        </div>
    </div>
    <!-------------TabCon5-随游管理------------->
    <div class="tab-div mySuiyou TabCon clearfix">
        <ul class="myOderNav tabTitle">
            <li><a href="#" class="active" id="unConfirmOrderManager">随游订单</a></li>
            <li><a href="#" id="myTripManager">我的随游</a></li>
            <li><a href="#" id="myJoinTripManager">我加入的随游</a></li>
        </ul>
        <div class="myOder past01 tabCon" style="display:block;" id="unConfirmList">
        </div>
        <div class="myOder past02 tabCon" id="myTripList">
        </div>
        <div class="myOder past03 tabCon" id="myJoinTripList">
            <div class="orderList clearfix">
                <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish">
                <dl class="order clearfix">
                    <dt class="title">
                        <span>15.4.20发布</span><span>随游</span><span>随游时间</span><span>最多接待人数</span><span>附加服务</span>
                    </dt>
                    <dd>
                        <span class="pic"><img src="/assets/images/2.png"></span>
                        <span>日本京都奈良公园一日游秀公园...</span>
                        <span>9:00AM</span>
                        <span>3</span>
                        <span>接机<b></b><br>租车<b></b></span>
                    </dd>
                </dl>
                <p><a href="#" class="cancel">离开随游</a></p>
            </div>
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
                    <img id="img_origin" style="display: none" border="0"/>
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
                            })

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
                        <span class="fl">输入验证码</span>
                        <input type="text" class="text fl"  maxlength="6" id="code">
                        <input type="button" value="获取验证码" class="btn fl" id="getCode">
                    </p>
                    <p><span>邮箱验证:</span><span id="emailTip" class="form_tip"></span></p>
                    <p class="p1 p2">
                        <span class="fl">输入邮箱</span>
                        <input type="text" class="text fl" value="<?= $this->context->userObj->email?>">
                        <input type="button" value="邮箱验证" class="btn fl">
                    </p>
                    <div style="clear: both"></div>
                    <p><span>实名认证</span><span class="form_tip"></span></p>
                    <div style="clear: both"></div>
                    <div class="sel-pic">
                        <input  class="sect" type="button" value="上传护照照片"/>
                        <input  class="btn sure" type="button" value="上传"/>
                    </div>
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
                    <p><a href="#"  class="surebtn">保存修改</a></p>

                </div>
            </div>
        </div>
        <div class="InformationCon past03 myCon">
            <div class="wdzl clearfix">
                <div class="wdzl-xx">
                    <p class="Mtitle">密码设置</p>
                    <span>旧密码:</span>
                    <input type="password">
                    <span>新密码:</span>
                    <input type="password">
                    <span>确认密码:</span>
                    <input type="password">
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
                    <a href="#"  class="surebtn">保存修改</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-----------个人中心-end--------------->
<script type="text/javascript" src="/assets/js/myTab.js"></script>
<script type="text/javascript" src="/assets/plugins/imgAreaSelect/js/jquery.imgareaselect.js" ></script>
<script type="text/javascript" src="/assets/plugins/jquery-uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript" src="/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" ></script>
<script type="text/javascript" src="/assets/plugins/select2/select2.min.js"></script>

<script type="text/javascript">
    var tripServiceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?>';
    var tripServiceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?>';
    var isPublisher=<?=$this->context->userObj->isPublisher?'true':'false';?>;

    var rotateCount=0;
    var containerDivWidth=300;
    var imgAreaSelectApi;

    var userProfession='<?=$this->context->userObj->profession?>';
    var userSex='<?=$this->context->userObj->sex?>';
    var cityId='<?=$this->context->userObj->cityId; ?>';


    var phoneTime = 0;
    var phoneTimer;

    $(document).ready(function(){

        if(isPublisher){
            $("#myTripManager").bind("click",function(){
                getMyTripList();
            });
            $("#myJoinTripManager").bind("click",function(){
                getMyJoinTripList();
            });
            $("#unConfirmOrderManager").bind("click",function(){
                getUnConfirmOrderByPublisher();
            });

            getUnConfirmOrderByPublisher();

        }else{
            $("#tripManager").parent("li").hide();
        }


        $("#unFinishOrderManager").bind("click",function(){
            getUnFinishList();
        });
        $("#finishOrderManager").bind("click",function(){
            getFinishList();
        });

        $(".con-nav li").bind("click",function(){
            resetUploadHeadImg();
        });

        $("#updateInfoBtn").bind("click",function(){
            updateUserInfo();
        });

        //绑定发送验证码事件
        $("#getCode").bind("click", function () {
            sendTravelCode();
        });

        getUnFinishList();
        initUploadImg();
        initTab();
        initUserInfo();
        initDatePicker();
        initSelect();



    });

    function updateUserInfo(){
        var sex=$('input:radio[name="sex"]:checked').val();
        var nickname=$("#nickname").val();
        var birthday=$("#birthday").val();
        var intro=$("#intro").val();
        var info=$("#info").val();
        var countryId=$("#countryId").val();
        var cityId=$("#cityId").val();
        var lon=$("#lon").val();
        var lat=$("#lat").val();
        var profession=$("input:radio[name='profession']:checked").val();
        if(profession=='其他'){
            profession=$("#ther").val();
        }
        if(nickname==''){

        }
        if($.trim(nickname)==''||$.trim(nickname)>30){
            $("#nicknameTip").html("昵称格式不正确");
            return;
        }
        if($.trim(countryId)==''){
            $("#cityTip").html("请选择居住地国家");
            return;
        }
        if($.trim(cityId)==''||$.trim(nickname)>30){
            $("#cityTip").html("请选择居住地城市");
            return;
        }

        $.ajax({
            url :'/user-info/update-user-info',
            type:'post',
            data:{
                sex:sex,
                nickname:nickname,
                birthday:birthday,
                intro:intro,
                info:info,
                countryId:countryId,
                cityId:cityId,
                lon:lon,
                lat:lat,
                profession:profession
            },
            beforeSend:function(){
            },
            error:function(){
                Main.showTip("更新用户信息失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                     Main.showTip("跟新用户信息成功");
                    window.location.href=window.location.href;
                }else{
                    Main.showTip("更新用户信息失败");
                }
            }
        });



    }
    //获取地区详情
    function findCityInfo(obj) {
        var name=$(obj).val();
        if(name==""){
            return;
        }
        $.ajax({
            url :'/google-map/search-map-info?search='+name,
            type:'get',
            data:{},
            beforeSend:function(){
            },
            error:function(){
                Main.showTip("获取地区坐标失败,未知系统异常");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    $("#lon").attr("lon",data.data.lng);
                    $("#lat").attr("lat",data.data.lat);

                    window.frames['mapFrame'].setMapSite(data.data.lng,data.data.lat);
                }else{
                    Main.showTip("获取地区坐标失败,未知系统异常");
                }
            }
        });
    }

    function initSelect(){
        //初始化国家，城市
        $(".select2").select2({
            'width':'350px',
            containerCss: {
                'margin-bottom':'20px'
            },
            formatNoMatches: function () {
                return "暂无匹配数据";
            }
        });


        //绑定获取城市列表
        $("#countryId").on("change", function () {
            $("#countryTip").html("");
            getCityList();
        });
        $("#cityId").on("change", function () {
            if($("#cityId").val()!=""){
                $("#cityTip").html("");
            }
            var search=$("#cityId").find("option:selected").text();
            if(search!=''){
                findCityInfo(search);
            }
        });
        $("#countryId").change();

        //初始化区号选择
        $(".areaCodeSelect").select2({
            'width':'130px',
            formatNoMatches: function () {
                return "暂无匹配";
            }
        });
    }
    function initDatePicker(){
        $('#birthday').datetimepicker({
            language:  'zh-CN',
            autoclose:1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format:'yyyy-mm-dd',
            weekStart: 1
        });
        $(".datetimepicker").hide();


        $('#birthday').unbind("focus");

        $("#birthday").bind("focus",function(){
            var top=$("#birthday").offset().top;
            var left=$("#birthday").offset().left;
            $(".datetimepicker").css({
                'top':top+40,
                'left':left,
                'position':'absolute',
                'background-color':'white',
                'border':'1px solid gray',
                'font-size':'14px'
            });
            $(".datetimepicker").show();
        });

        $(".table-condensed tbody").bind("click",function(){
            $(".datetimepicker").hide();
        });
    }

    function initUserInfo()
    {
        //init sex
        if(userSex==0){
            $("input:radio[name='sex'][value=0]").attr("checked",true);
            $("#rado2").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else if(userSex==1){
            $("input:radio[name='sex'][value=1]").attr("checked",true);
            $("#rad01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else{
            $("input:radio[name='sex'][value=2]").attr("checked",true);
            $("#rad03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }

        if(userProfession=='持证导游'){
            $("input:radio[name='profession'][value='持证导游']").attr("checked",true);
            $("#shenfen01").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else if(userProfession=='业余导游'){
            $("input:radio[name='profession'][value='业余导游']").attr("checked",true);
            $("#shenfen02").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else if(userProfession=='学生'){
            $("input:radio[name='profession'][value='学生']").attr("checked",true);
            $("#shenfen03").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else if(userProfession=='旅游爱好者'){
            $("input:radio[name='profession'][value='旅游爱好者']").attr("checked",true);
            $("#shenfen04").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }else{
            $("input:radio[name='profession'][value='其他']").attr("checked",true);
            $("#shenfen05").next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
        }

    }

    function initTab(){
        var href=window.location.href;
        var tabId='';
        if(href.indexOf("?")!=-1){
            tabId=href.substring(href.indexOf("?")+1,href.length);
            $("#"+tabId).click();
        }
    }

    //初始化上传插件
    function initUploadImg(){

        $('#reImg').uploadifive({
            'auto': true,
            'queueID': 'reQueue',
            'uploadScript': '/upload/upload-head-img',
            'multi': false,
            'dnd': false,
            'onUpload':function(){
                $("#uploadBtn").val("正在上传，请稍后");
            },
            'onUploadComplete': function (file, data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#uploadBtn").val("上传成功！");
                    $("#img_src").val(datas.data);
                    $("#img_origin").attr("src",datas.data);
                    $(".p_photo1 img").attr("src",datas.data);
                    $(".p_photo2 img").attr("src",datas.data);
                    $(".p_photo3 img").attr("src",datas.data);

                    $("#img_origin").show();
                    $("#uploadBtn").hide();
                    initImgAreaSelect("#img_origin");
                } else {
                    $("#uploadBtn").val("上传失败，请重试");
                }
            }
        });
        $("#uploadBtn").bind("click",function(){
            $("#uploadifive-reImg input[type='file'][id!='titleImgFile']").last().click();
        });
        $("#uploadImgConfirm").bind("click",function(){
            selectImg();
        });
        $("#uploadImgCancle").bind("click",function(){
            resetUploadHeadImg();
        });

    }

    function resetUploadHeadImg(){
        removeImgAreaSelect();
        $("#uploadBtn").val("点击上传图片");
        $("#uploadBtn").show();
        $("#img_origin").hide();
        $("#img_origin").attr("src","");
        $("#img_src").val();
    }

    function selectImg(){
        var x=$("#img_x").val();
        var y=$("#img_y").val();
        var w=$("#img_w").val();
        var h=$("#img_h").val();
        var rotate=$("#img_rotate").val();
        var imgSrc=$("#img_src").val();
        if(imgSrc==""){
            Main.showTip("您还没有选择图片哦！");
            return;
        }
        if(w==0||h==0){
            Main.showTip("请正确选择图片！");
            return;
        }
        if(isNaN(w)||isNaN(h)){
            Main.showTip("请正确选择图片！");
            return;
        }
        $.ajax({
            url: "/user-info/change-user-head-img",
            type: "post",
            data:{
                "x":x,
                "y":y,
                "w":w,
                "h":h,
                "rotate":rotate,
                "src":imgSrc,
                "pWidth":$("#img_origin").width(),
                "pHeight":$("#img_origin").height()
            },
            error:function(){
                alert("上传头像异常，请刷新重试！");
            },
            success: function(data){
                var result=eval("("+data+")");
                if(result.status==1){
                    $(".userPic img").attr("src",result.data);
                    resetUploadHeadImg();
                }else{
                    alert("上传头像异常，请刷新重试！");
                }
            }
        });
    }
    function resetImg(){
        imgAreaSelectApi.update();
    }

    function removeImgAreaSelect(){
        imgAreaSelectApi.cancelSelection();
    }
    function initImgAreaSelect(imgObj){
        imgAreaSelectApi = $(imgObj).imgAreaSelect({
            instance : true,	// true，返回一个imgAreaSelect绑定到的图像的实例，可以使用api方法
            onSelectChange : preview,	// 改变选区时的回调函数
            handles : true,	// true，调整手柄则会显示在选择区域内
            fadeSpeed:200,
            resizable : true,
            aspectRatio:"1:1"

        });
        imgAreaSelectApi.setRotate(0);
        resetRotate();
    }

    $('#img_origin').load(function(){
        var form = $('#coordinates_form');

        //获取 x、y、w、h的值
        var left = parseInt(form.children('.x').val());
        var top = parseInt(form.children('.y').val());
        var width = parseInt(form.children('.w').val());
        var height = parseInt(form.children('.h').val());

        //imgAreaSelectApi 就是图像img_origin的实例 上边instance已解释
        //setSelection(),设置选区的坐标
        //update(),更新
        imgAreaSelectApi.setSelection(left, top, left+width, top+height);
        imgAreaSelectApi.update();

        //图片居中
        var imgWidth=$("#img_origin").width();
        var imgHeight=$("#img_origin").height();
        $("#img_origin").css("margin","0")
        if(imgWidth<containerDivWidth&&imgHeight<containerDivWidth){
            if(imgWidth>imgHeight){
                $("#img_origin").width(containerDivWidth);
            }else{
                $("#img_origin").height(containerDivWidth);
            }
        }
        imgWidth=$("#img_origin").width();
        imgHeight=$("#img_origin").height();

        if(imgWidth>=imgHeight){
            var padding=(imgWidth-imgHeight)/2;
            $("#img_origin").css("margin-top",padding);
            imgAreaSelectApi.setSelection((imgWidth/2)-(imgHeight/4), (imgHeight/2)-(imgHeight/4), (imgWidth/2)+(imgHeight/4), (imgHeight/2)+(imgHeight/4), true);
        }
        if(imgHeight>imgWidth){
            var padding=(imgHeight-imgWidth)/2;
            $("#img_origin").css("margin-left",padding);
            imgAreaSelectApi.setSelection((imgHeight/2)-(imgWidth/4)-padding, (imgHeight/2)-(imgWidth/4), (imgHeight/2)+(imgWidth/4)-padding, (imgHeight/2)+(imgWidth/4), true);
        }


        imgAreaSelectApi.setOptions({ show: true });

        imgAreaSelectApi.update();
        preview($("#img_origin"),imgAreaSelectApi.getSelection());

    });

    function preview(img, selection){

        var form = $('#coordinates_form');
        //重新设置x、y、w、h的值
        form.children('.x').val(selection.x1);
        form.children('.y').val(selection.y1);
        form.children('.w').val(selection.x2-selection.x1);
        form.children('.h').val(selection.y2-selection.y1);
        form.children('.rotate').val(imgAreaSelectApi.getRotate());
        preview_photo('p_photo1', selection);
        preview_photo('p_photo2', selection);
        preview_photo('p_photo3', selection);


    }
    function preview_photo(div_class, selection){
        var div = $('div.'+div_class);

        //获取div的宽度与高度
        var width = div.outerWidth();
        var height = div.outerHeight();
        var scaleX = width/selection.width;
        var scaleY = height/selection.height;

        div.find('img').css({
            width : Math.round(scaleX * $('#img_origin').outerWidth())+'px',
            height : Math.round(scaleY * $('#img_origin').outerHeight())+'px',
            marginLeft : '-'+Math.round(scaleX * selection.x1)+'px',
            marginTop : '-'+Math.round(scaleY * selection.y1)+'px'
        });
    }

    function resetRotate(){
        rotateCount=0;
        var du=0;
        rotate(document.getElementById("crop_container"), du);
        rotate(document.getElementById("p_photo"),du);
        imgAreaSelectApi.setRotate(du);
        imgAreaSelectApi.update();
    }


    /**
     * 获取我的随游
     */
    function getMyTripList()
    {
        $.ajax({
            url :'/trip/my-trip-list',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取我的随游失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    $("#myTripList").html(buildMyTripHtml(data.data));
                }else{
                    Main.showTip("获取我的随游失败");
                }
            }
        });
    }

    /**
     * 构建我的随游HTML
     * @param tripList
     * @returns {string}
     */
    function buildMyTripHtml(tripList)
    {
        if(tripList==''||tripList.length==0){
            return '';
        }
        var tripInfo,html='';
        for(var i=0;i<tripList.length;i++){
            tripInfo=tripList[i];
            var count=tripInfo.count==null?'':tripInfo.count;
            if(count!=''){ count='<a href="/trip/to-apply-list?trip='+tripInfo.tripId+'" class="sure">新申请</a><b>'+count+'</b>'};
            html+='<div class="orderList clearfix">';
            html+=' <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteTravelTrip('+tripInfo.tripId+',this)">';
            html+=' <dl class="order clearfix">';
            html+='   <dt class="title">';
            html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+tripInfo.title+'</span><span>随游时间</span><span>附加服务</span>';
            html+='   </dt>';
            html+='   <dd>';
            html+='       <span class="pic"><img src="'+tripInfo.titleImg+'"></span>';
            html+='       <span>'+tripInfo.info+'</span>';
            html+='       <span>'+tripInfo.startTime+'</span>';
            html+='       <span>';
            if(tripInfo.names!=''&&tripInfo.names!=null){
                var names=tripInfo.names.split(",");
                for(var j=0;j<names.length;j++){
                    html+=names[j]+'<b></b><br>';
                }
            }
            html+='        </span>';
            html+='   </dd>';
            html+=' </dl>';
            html+=' <p><a href="/view-trip/info?trip='+tripInfo.tripId+'" class="cancel">查看详情</a>'+count+'</p>';
            html+='</div>';
        }
        return html;
    }

    /**
     * 获取我加入的随游
     */
    function getMyJoinTripList()
    {
        $.ajax({
            url :'/trip/my-join-trip-list',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取我加入的随游失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    $("#myJoinTripList").html(buildMyJoinTripHtml(data.data));
                }else{
                    Main.showTip("获取我加入的随游失败");
                }
            }
        });
    }

    /**
     * 构建我加入的随游HTML
     * @param tripList
     * @returns {string}
     */
    function buildMyJoinTripHtml(tripList)
    {
        if(tripList==''||tripList.length==0){
            return '';
        }
        var tripInfo,html='';
        for(var i=0;i<tripList.length;i++){
            tripInfo=tripList[i];
            var count=tripInfo.count==null?'':tripInfo.count;
            if(count!=''){ count='<a href="#" class="sure">新申请</a><b>'+count+'</b>'};
            html+='<div class="orderList clearfix">';
            html+=' <dl class="order clearfix">';
            html+='   <dt class="title">';
            html+='       <span>'+Main.formatDate(tripInfo.createTime,'yyyy.MM.dd')+'发布</span><span>'+tripInfo.title+'</span><span>随游时间</span><span>附加服务</span>';
            html+='   </dt>';
            html+='   <dd>';
            html+='       <span class="pic"><img src="'+tripInfo.titleImg+'"></span>';
            html+='       <span>'+tripInfo.info+'</span>';
            html+='       <span>'+tripInfo.startTime+'</span>';
            html+='       <span>';
            if(tripInfo.names!=''&&tripInfo.names!=null){
                var names=tripInfo.names.split(",");
                for(var j=0;j<names.length;j++){
                    html+=names[j]+'<b></b><br>';
                }
            }
            html+='        </span>';
            html+='   </dd>';
            html+=' </dl>';
            html+=' <p><a href="/view-trip/info?trip='+tripInfo.tripId+'" class="cancel">查看详情</a>'+count+'</p>';
            html+='</div>';
        }
        return html;
    }


    /**
     * 获取用户未完成的订单
     */
    function getUnFinishList()
    {
        $.ajax({
            url :'/user-order/get-un-finish-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取我的未完成订单失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    $("#unFinishList").html(buildOrderList(data.data,0));
                }else{
                    Main.showTip("获取我的未完成订单失败");
                }
            }
        });
    }

    /**
     * 获取用户已完成的订单
     */
    function getFinishList()
    {
        $.ajax({
            url :'/user-order/get-finish-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取我的完成订单失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    $("#finishList").html(buildOrderList(data.data,1));
                }else{
                    Main.showTip("获取完成订单失败");
                }
            }
        });
    }

    /**
     * 构建用户订单页面
     * @param list
     * @param type
     * @returns {string}
     */
    function buildOrderList(list,type)
    {
        var html="";
        if(list==""||list.length==0){
            return html;
        }
        for(var i=0;i<list.length;i++){
            var orderInfo=list[i];
            var travelInfo=orderInfo.tripJsonInfo;
            travelInfo=eval("("+travelInfo+")");
            var serviceInfo=orderInfo.serviceInfo;
            serviceInfo=eval("("+serviceInfo+")");
            html+='<div class="orderList clearfix">';
            if(type==1){
                html+='<img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish">'
            }
            html+='<dl class="order clearfix">';
            html+='<dt class="title">';
            html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>随游</span><span>开始时间</span><span>随友</span><span>随友电话</span><span>出行日期</span><span>人数</span><span>单项服务</span>';
            html+='</dt>';
            html+='<dd>';
            html+='<span class="pic"><img src="'+travelInfo.info.titleImg+'"></span>';
            html+='<span>'+travelInfo.info.title+'</span>';
            html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
            if(orderInfo.phone==''||orderInfo.phone==null){
                html+='<span>未接单</span>';
                html+='<span>未接单</span>';
            }else{
                html+='<span><a href="#" class="user"><img src="'+orderInfo.headImg+'"  width="40" height="40"></a><a href="#" class="message"><b>'+orderInfo.nickname+'</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
                html+='<span>'+orderInfo.phone+'</span>';
            }

            html+='<span>'+orderInfo.beginDate+'</span>';
            html+='<span>'+orderInfo.personCount+'</span>';
            html+='<span>';
            if(serviceInfo!=''&&serviceInfo.length>0){
                for(var j=0;j<serviceInfo.length;j++){
                    var service=serviceInfo[j];
                    html+=service.title+'<b>'+service.money+'</b>';
                    if(service.type==tripServiceTypePeople){
                        html+='/人';
                    }else{
                        html+='/次';
                    }
                    html+='<br>';
                }
            }
            html+='</span>';
            html+='</dd>';
            html+='</dl>';
            if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_WAIT){
                html+='<p><a href="#" class="cancel">取消订单</a><a href="#" class="sure">支付</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">待支付</span><span class="orange"></span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PAY_SUCCESS) {
                html+='<p><a href="#" class="cancel">取消订单</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">已支付</span><span class="orange">待接单</span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CONFIRM){
                html+='<p><a href="#" class="cancel">申请退款</a><a href="#" class="sure">确认游玩</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">已支付</span><span class="orange">已确认</span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_CANCELED){
                html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">已取消</span><span class="orange"></span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_WAIT){
                html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">等待退款</span><span class="orange"></span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_REFUND_SUCCESS){
                html+='<p><a href="#" class="cancel"></a><a href="#" class="sure"></a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">退款成功</span><span class="orange"></span></p>';
            }else if(orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_SUCCESS||orderInfo.status==OrderStatus.USER_ORDER_STATUS_PLAY_FINISH){
                html+='<p><a href="#" class="cancel">去评价</a><a href="#" class="sure">分享</a><span>总价：<b>'+orderInfo.totalPrice+'</b></span>';
                html+='<span class="blue">已完成</span><span class="orange"></span></p>';
            }
            html+='</div>';
        }
        return html;
    }

    /**
     * 获取随友可接收的订单
     */
    function getUnConfirmOrderByPublisher()
    {
        $.ajax({
            url :'/user-order/get-un-confirm-order',
            type:'post',
            data:{
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("获取可接受随游订单失败");
            },
            success:function(data){
                //hide load
                data=eval("("+data+")");
                if(data.status==1){
                    $("#unConfirmList").html(buildUnConfirmList(data.data,1));
                }else{
                    Main.showTip("获取可接受随游订单失败");
                }
            }
        });
    }

    /**
     * 构建可接受订单
     * @param list
     * @returns {string}
     */
    function buildUnConfirmList(list)
    {
        var html="";
        if(list==""||list.length==0){
            return html;
        }
        for(var i=0;i<list.length;i++){
            var orderInfo=list[i];
            var travelInfo=orderInfo.tripJsonInfo;
            travelInfo=eval("("+travelInfo+")");
            var serviceInfo=orderInfo.serviceInfo;
            serviceInfo=eval("("+serviceInfo+")");

            html+='<div class="orderList clearfix">';
            html+='<dl class="order clearfix">';
            html+='<dt class="title">';
            html+='<span>'+Main.convertOrderDateToShow(orderInfo.createTime)+'</span><span>申请随游</span><span>开始时间</span><span>申请游客</span><span>出行时间</span><span>人数</span><span>附加服务</span>';
            html+='</dt>';
            html+='<dd>';
            html+='<span class="pic"><img src="'+travelInfo.info.titleImg+'"></span>';
            html+='<span>'+travelInfo.info.title+'</span>';
            html+='<span>'+Main.convertTimePicker(orderInfo.startTime,2)+'</span>';
            html+='<span><a href="#" class="user"><img src="'+orderInfo.headImg+'" width="40" height="40"></a><a href="#" class="message"><b>'+orderInfo.nickname+'</b><br><img src="/assets/images/xf.fw.png" width="18" height="12"></a></span>';
            html+='<span>'+orderInfo.beginDate+'</span>';
            html+='<span>'+orderInfo.personCount+'</span>';
            html+='<span>';
            if(serviceInfo!=''&&serviceInfo.length>0){
                for(var j=0;j<serviceInfo.length;j++){
                    var service=serviceInfo[j];
                    html+=service.title+'<b>'+service.money+'</b>';
                    if(service.type==tripServiceTypePeople){
                        html+='/人';
                    }else{
                        html+='/次';
                    }
                    html+='<br>';
                }
            }
            html+='</span>';
            html+='</dd>';
            html+='</dl>';
            html+='<p><a href="javascript:publisherIgnoreOrder('+orderInfo.orderId+');" class="cancel">忽略</a><a href="javascript:publisherConfirmOrder('+orderInfo.orderId+');" class="sure">接受</a></p>';
            html+='</div>';
        }
        return html;

    }


    /**
     * 确认用户订单
     * @param orderId
     */
    function publisherConfirmOrder(orderId)
    {
        if(orderId==''){
            return;
        }
        $.ajax({
            url :'/user-order/publisher-confirm-order',
            type:'post',
            data:{
                orderId:orderId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("抢单失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    Main.showTip("抢单成功");
                    getUnConfirmOrderByPublisher();
                }else{
                    Main.showTip("抢单失败");
                }
            }
        });
    }

    /**
     * 忽略用户订单
     * @param orderId
     */
    function publisherIgnoreOrder(orderId)
    {
        if(orderId==''){
            return;
        }
        $.ajax({
            url :'/user-order/publisher-ignore-order',
            type:'post',
            data:{
                orderId:orderId,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("忽略订单失败");
            },
            success:function(data){
                data=eval("("+data+")");
                if(data.status==1){
                    getUnConfirmOrderByPublisher();
                }else{
                    Main.showTip("忽略订单失败");
                }
            }
        });
    }


    /**
     * 删除随游
     * @param tripId
     */
    function deleteTravelTrip(tripId,obj)
    {
        $.ajax({
            url :'/trip/delete-trip',
            type:'post',
            data:{
                tripId:tripId,
                _csrf: $('input[name="_csrf"]').val()

            },
            error:function(){
               Main.showTip("删除随游失败");
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.showTip("删除随游成功");
                    $(obj).parent("div").remove();
                }else{
                    Main.showTip("删除随游失败");
                }
            }
        });
    }

    //级联获取城市列表
    function  getCityList(){
        var countryId=$("#countryId").val();
        if(countryId==""){
            return;
        }
        $("#countryTip").html("");
        $("#cityId").empty();

        $("#cityId").append("<option value=''></option>");
        $("#cityId").val("").trigger("change");
        $.ajax({
            url :'/country/find-city-list',
            type:'post',
            data:{
                countryId:countryId,
                _csrf: $('input[name="_csrf"]').val()

            },
            error:function(){
                $("#cityTip").html("获取城市列表失败");
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    var html = "";
                    for(var i=0;i<datas.data.length;i++){
                        var city=datas.data[i];
                        html+='<option value="'+city.id+'">'+city.cname+"/"+city.ename+'</option>';
                    }
                    $("#cityId").append(html);
                    if(cityId!=""){
                        $("#cityId").val(cityId).trigger("change");
                    }
                }else{
                    $("#cityTip").html("获取城市列表失败");
                }
            }
        });
    }

    /**
     * 发送手机验证码
     */
    function sendTravelCode() {
        //TODO 验证手机有效性
        var phone = $("#phone").val();
        var areaCode = $("#codeId").val();

        if (phone == "") {
            $("#phoneTip").html("请输入有效的手机号");
            return;
        } else {
            $("#phoneTip").html("");
        }
        $.ajax({
            url: '/index/send-travel-code',
            type: 'post',
            data: {
                phone: phone,
                areaCode: areaCode,
                _csrf: $('input[name="_csrf"]').val()

            },
            beforeSend: function () {
                $("#getCode").val("正在发送...");
            },
            error: function () {
                $("#getCode").val("发送失败...");
            },
            success: function (data) {
                var datas = eval('(' + data + ')');
                if (datas.status == 1) {
                    $("#getCode").val("发送成功");
                    phoneTime = 60;
                    initPhoneTimer();
                } else {
                    $("#getCode").val("发送失败...");
                    $("#phoneTip").html(datas.data);
                }
            }
        });
    }

    function initPhoneTimer() {
        phoneTimer = window.setInterval(function () {
            if (phoneTime > 0) {
                phoneTime--;
                initPhoneTime();
            } else {
                window.clearInterval(phoneTimer);
                initPhoneTime();
            }
        }, 1000);
    }

    function initPhoneTime() {

        if (phoneTime != "" && phoneTime > 0) {
            $("#getCode").val(+phoneTime + "秒后重新发送");
            $("#getCode").attr("disabled", "disabled");

            $("#getCode").css("background", "gray");
            $("#getCode").unbind("click");
        } else {
            $("#getCode").val("获取验证码");
            $("#getCode").removeAttr("disabled");
            $("#getCode").css("background", "#ff7a4d");
            $("#getCode").unbind("click");
            $("#getCode").bind("click", function () {
                sendTravelCode();
            });
        }
    }



</script>