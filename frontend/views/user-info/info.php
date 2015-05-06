<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:19
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<script type="text/javascript" src="/assets/js/myTab.js"></script>
<!--------------header-end-------------->
<!------------banner----------------->
<div class="syTop">
    <div class="banner">
        <div class="banner-inner">
            <a href="javascript:;" class="btn">设置</a>
        </div>
    </div>
    <div class="user w1200">
        <a href="#" class="userPic"><img src="<?=$this->context->userObj->headImg ?>" width="122px" alt=""></a>
        <span><?=$this->context->userObj->intro ?></span>
        <p><?=$this->context->userObj->intro ?></p>
        <ul>
            <li>姓名:<b>小A</b></li>
            <li>性别:<b><?php if($this->context->userObj->sex==\common\entity\UserBase::USER_SEX_MALE){echo '男';}elseif($this->context->userObj->sex==\common\entity\UserBase::USER_SEX_FEMALE){echo '女';}else{echo '保密';} ?></b></li>
            <li>年龄:<b><?=\common\components\DateUtils::convertBirthdayToAge($this->context->userObj->birthday)?></b></li>
            <li>城市:<b>北京</b></li>
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
        <li><a href="#">个人资料</a></li>
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
            <div class="wdzl clearfix">
                <div class="sel-pic">
                    <input  class="sect" type="button" value="点击上传照片"/>
                    <input  class="btn sure" type="button" value="确定"/>
                    <input  class="btn cancel" type="button" value="取消"/>
                </div>
                <div class="wdzl-img clearfix">
                    <img src="/assets/images/3.png" alt="">
                    <img src="/assets/images/4.png" alt="" class="m27">
                    <img src="/assets/images/5.png" alt="" class="m40">
                    <input type="button" value="上传" class="wdzl-btn">
                </div>
                <div class="radio">
                    <span>性别：</span>
                    <div class="sexs">
                        <form name="form1" method="post" action="">
                            <input type="radio" value="man" id="rad01" name="sex">
                            <label for="rad01">男</label>
                            <input type="radio" value="woman" id="rad02" name="sex">
                            <label for="rad02">女</label>
                            <input type="radio" value="woman" id="rad03" name="sex">
                            <label for="rad03">保密</label>
                        </form>
                    </div>
                </div>
                <div class="wdzl-xx">
                    <span>昵称:</span>
                    <input type="text" value="" class="wdzj-text">
                    <span>日期:</span>
                    <input type="text" value="" class="wdzj-text">
                    <span>个性签名:</span>
                    <input type="text" value="" class="wdzj-text">
                    <span>常住地:</span>
                    <div>
                        <select data-enabled="false">
                            <option value="zg">国家</option>
                            <option value="mg">美国</option>
                            <option value="hg">韩国</option>
                            <option value="zg">国家</option>
                            <option value="mg">美国</option>
                            <option value="hg">韩国</option>
                        </select>
                        <select data-enabled="false">
                            <option value="zg">城市</option>
                            <option value="">洛杉矶</option>
                            <option value="">首尔</option>
                        </select>
                    </div>
                    <div class="map">
                        <img src="/assets/images/map-pic.png">
                    </div>
                    <span>身份:</span>
                    <div class="shenfen">
                        <input type="radio" value="" id="shenfen01" name="sex">
                        <label for="shenfen01">持证导游</label>
                        <input type="radio" value="" id="shenfen02" name="sex">
                        <label for="shenfen02">业余导游</label>
                        <input type="radio" value="" id="shenfen03" name="sex">
                        <label for="shenfen03">学生</label>
                        <input type="radio" value="" id="shenfen04" name="sex">
                        <label for="shenfen04">旅游爱好者</label>
                        <input type="radio" value="" id="shenfen05" name="sex">
                        <label for="shenfen05">其他</label>
                        <input type="text" class="other">

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
                    <span>手机号验证:</span>
                    <div class="phone-select">
                        <div class="sect">
                            <select data-enabled="false">
                                <option value="zg" class=" selected">区号</option>
                                <option value="mg">0345</option>
                                <option value="hg">3456</option>
                                <option value="zg">6777</option>
                                <option value="mg">7777</option>
                                <option value="hg">7754</option>
                            </select>
                        </div>
                        <input type="text" value="" class="phone fl" >
                    </div>
                    <p class="p1">
                        <span class="fl">输入验证码</span>
                        <input type="text" class="text fl">
                        <input type="button" value="获取验证码" class="btn fl">
                    </p>
                    <span>个人简介:</span>
                    <textarea class="textarea"></textarea>
                    <a href="#"  class="surebtn">保存修改</a>
                </div>
            </div>
        </div>
        <div class="InformationCon past02 myCon">
            <div class="wdzl clearfix">
                <div class="wdzl-xx">
                    <span>手机号验证:</span>
                    <div class="phone-select">
                        <div class="sect">
                            <select data-enabled="false">
                                <option value="zg" class=" selected">区号</option>
                                <option value="mg">0345</option>
                                <option value="hg">3456</option>
                                <option value="zg">6777</option>
                                <option value="mg">7777</option>
                                <option value="hg">7754</option>
                            </select>
                        </div>
                        <input type="text" value="" class="phone fl" >
                    </div>
                    <p class="p1">
                        <span class="fl">输入验证码</span>
                        <input type="text" class="text fl">
                        <input type="button" value="获取验证码" class="btn fl">
                    </p>
                    <span>个人简介:</span>
                    <textarea class="textarea"></textarea>
                    <span></span>
                    <span>邮箱验证:</span>
                    <p class="p1 p2">
                        <span class="fl">输入邮箱</span>
                        <input type="text" class="text fl">
                        <input type="button" value="邮件获取验证码" class="btn fl">
                    </p>
                    <span>实名认证</span>
                    <div class="sel-pic">
                        <input  class="sect" type="button" value="上传护照照片"/>
                        <input  class="btn sure" type="button" value="上传"/>
                    </div>
                    <span>更多认证</span>
                    <span></span>
                    <div class="moreRen">
                        <ul>
                            <li><b class="icon sina"></b><input class="active" type="button" value="关联"></li>
                            <li><b class="icon weixin"></b><input type="button" value="关联"></li>
                            <li><b class="icon qq"></b><input type="button" value="关联"></li>
                        </ul>
                    </div>
                    <span></span>
                    <a href="#"  class="surebtn">保存修改</a>
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
                    <p class="Mtitle">支付设置</p>
                    <span></span>
                    <div class="moreRen">
                        <ul>
                            <li><b class="icon zfb"></b><input class="active" type="button" value="关联"></li>
                            <li><b class="icon weixin"></b><input type="button" value="关联"></li>
                            <li><b class="icon sina"></b><input type="button" value="关联"></li>
                        </ul>
                    </div>
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

<script type="text/javascript">
    var tripServiceTypeCount='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_COUNT?>';
    var tripServiceTypePeople='<?=\common\entity\TravelTripService::TRAVEL_TRIP_SERVICE_TYPE_PEOPLE?>';
    var isPublisher=<?=$this->context->userObj->isPublisher?'true':'false';?>;
    $(document).ready(function(){
        if(isPublisher){
            $("#tripManager").bind("click",function(){
                getUnConfirmOrderByPublisher();
            });
            $("#myTripManager").bind("click",function(){
                getMyTripList();
            });
            $("#myJoinTripManager").bind("click",function(){
                getMyJoinTripList();
            });
            $("#unConfirmOrderManager").bind("click",function(){
                getUnConfirmOrderByPublisher();
            });

        }else{
            $("#tripManager").parent("li").hide();
        }
        
        $("#myOrderManager").bind("click",function(){
            getUnFinishList();
        });
        $("#unFinishOrderManager").bind("click",function(){
            getUnFinishList();
        });
        $("#finishOrderManager").bind("click",function(){
            getFinishList();
        });


    });


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
            html+=' <img src="/assets/images/delete.fw.png" width="22" height="24" class="rubbish" onclick="deleteTravelTrip('+tripInfo.tripId+')">';
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
    function deleteTravelTrip(tripId)
    {
        alert(tripId);
    }



</script>