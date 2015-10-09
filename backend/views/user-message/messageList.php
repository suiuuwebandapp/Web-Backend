<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/28
 * Time : 16:23
 * Email: zhangxinmailvip@foxmail.com
 */

?>
<script type="text/javascript" src="/assets/global/plugins/jquery-tmpl/jquery.tmpl.min.js"></script>

<style type="text/css">
    #sessionList ul {
    }

    #sessionList ul li {
        list-style: none;
        cursor: pointer;
        padding-left: 2px;
    }
    #sessionList ul li div.closeDiv{
        width: 10px;
        display: inline-block;
    }
    #sessionList ul li div.tip{
        width: 30px;
        display: inline-block;
        text-align: center;
    }
    #sessionList ul li div b{
        display: none;
    }
    #sessionList ul li.active {
        background-color: #EEEEEE;
    }

    #sessionList ul:first-child {
        margin-top: 0px;
    }

    #sessionList ul li img {
        border-radius: 60px;
        margin-right: 10px;
    }

    #sessionList .nickname {
        width: 133px;
        display: inline-block;
    }

    #sessionList .chats {
        height: 500px;
    }

    #sessionList .searchSession {
        margin-bottom: 10px;
    }
    #chats .nicknameTitle{
        font-size: 20px;
    }
</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-5">
        <div class="portlet">
            <div class="portlet-body" id="sessionList">
                <div class="searchSession input-group">
                    <input type="text" class="form-control" placeholder="请输入用户昵称">
					<span class="input-group-btn">
					    <button id="searchSessionList" type="submit" class="btn blue icn-only"><i class="icon-magnifier"></i></button>
					</span>
                </div>
                <div style="clear: both"></div>
                <div class="scroller" style="height: 500px;" data-always-visible="1" data-rail-visible1="1">
                    <ul class="chats clearfix" id="userSessionListUl">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="portlet">
            <div class="portlet-body" id="chats">
                <div>
                    <p class="nicknameTitle"></p>
                </div>
                <div class="scroller" style="height: 435px;" data-always-visible="1" data-rail-visible1="1">
                    <ul class="chats" id="messageListUl">
                    </ul>
                </div>
                <div class="chat-form">
                    <div class="input-cont">
                        <input class="form-control" type="text" placeholder="请输入消息内容">
                    </div>
                    <div class="btn-cont">
                        <span class="arrow"></span>
                        <a href="javascript:;" class="btn blue icn-only" id="sendMessage">
                            <i class="fa fa-check icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script id="sessionListTmpl" type="text/x-jquery-tmpl">
    <li sessionKey='${sessionKey}' userId='${userId}' relateId='${relateId}'>
        <div class="closeDiv"><b class="close"></b></div>
        <img class="avatar" alt="" src="${headImg}">
        <span class="nickname">${$item.getName(nickname)}</span>
        <img class="avatar" alt="" src="${relateHeadImg}">
        <span class="nickname">${$item.getName(relateNickname)}</span>
        <div class="tip">
            <span class="badge badge-danger">${$item.getUnReadCount(unReadCount)}</span>
        </div>
    </li>
</script>
<script id="messageListTmpl" type="text/x-jquery-tmpl">
    <li class="${$item.getType(senderId)}">
        <img class="avatar" alt="" src="${$item.getImg(senderId)}">
        <div class="message">
            <span class="arrow"></span>
                <a href="javascript:;" class="name">${$item.getName(senderId)} </a>
                <span class="datetime"> at ${sendTime} </span>
            <span class="body">${content}</span>
        </div>
    </li>
</script>
<script type="text/javascript">

    var cont = $('#chats');
    var list = $('.chats', cont);
    var form = $('.chat-form', cont);
    var input = $('input', form);
    var btn = $('.btn', form);
    var sessionList = $('#sessionList');

    /**
     * 获取当前格式化的时间
     * @returns {string}
     */
    var getNowFormatDate=function() {
        var date = new Date();
        var separator1 = "-";
        var separator2 = ":";
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var strHours=date.getHours();
        var strMinutes=date.getMinutes();
        var strSeconds=date.getSeconds();

        if (month >= 1 && month <= 9) {
            month = "0" + month;
        }
        if (strDate >= 0 && strDate <= 9) {
            strDate = "0" + strDate;
        }
        if (strHours >= 0 && strHours <= 9) {
            strHours = "0" + strHours;
        }
        if (strMinutes >= 0 && strMinutes <= 9) {
            strMinutes = "0" + strMinutes;
        }
        if (strSeconds >= 0 && strSeconds <= 9) {
            strSeconds = "0" + strSeconds;
        }
        var currentDate = date.getFullYear() + separator1 + month + separator1 + strDate
            + " " + strHours + separator2 + strMinutes
            + separator2 + strSeconds;
        return currentDate;
    };
    /**
     * 获取当前聊天详情内容高度
     * @returns {number}
     */
    var getLastPostPos = function () {
        var height = 0;
        cont.find("li.out, li.in").each(function () {
            height = height + $(this).outerHeight();
        });

        return height+50;
    };

    /**
     * 发送消息事件
     * @param e
     */
    var handleClick = function (e) {
        e.preventDefault();

        var text = input.val();
        if (text.length == 0) {
            return;
        }
        var time = new Date();
        var time_str = time.getFullYear()+'-'+time.getMonth()+'-'+time.getDay()+' '+time.getHours() + ':' + time.getMinutes()+':'+time.getSeconds();

        var selectSessionKey=$(sessionList).find("li[class='active']").eq(0).attr('sessionKey');
        var userId=$(sessionList).find("li[class='active']").eq(0).attr('userId');
        var relateId=$(sessionList).find("li[class='active']").eq(0).attr('relateId');
        var senderImg=$(sessionList).find("li[class='active']").find('img').eq(0).attr('src');
        var senderName=$(sessionList).find("li[class='active']").find("span[class='nickname']").eq(0).text();
        var receiveImg=$(sessionList).find("li[class='active']").find('img').eq(1).attr('src');
        var receiveName=$(sessionList).find("li[class='active']").find("span[class='nickname']").eq(1).text();
        var newMessageInfo=new Array();
        newMessageInfo.push({
            'sessionkey': selectSessionKey,
            'receiveId': relateId,
            'senderId': userId,
            'content': text,
            'sendTime': getNowFormatDate()
        });

        ws.send(JSON.stringify({"type": "sys_say","client_id":userId,"nickname":receiveName,"head_img":receiveImg,"to_client_id": relateId,"content":text}));

        $('#messageListTmpl').tmpl(newMessageInfo,{
            getType:function(userId){if(userId!=userId){return 'in';}else{return 'out';}},
            getImg:function(userId){if(userId!=userId){return senderImg;}else{return receiveImg;}},
            getName:function(userId){if(userId!=userId){return senderName;}else{return receiveName;}}
        }).appendTo('#messageListUl');
        $(input).val("")
        cont.find('.scroller').slimScroll({
            scrollTo: getLastPostPos()
        });
    };

    var searchSession=function(){
        var keywords=$('div.searchSession input').val();
        getMessageSessionList(keywords);
    };
    /**
     * 绑定默认事件
     */
    var initDefaultBtnClick=function(){
        btn.click(handleClick);
        input.keypress(function (e) {
            if (e.which == 13) {
                handleClick(e);
                return false; //<---- Add this line
            }
        });

        $("#searchSessionList").click(searchSession);
        $('div.searchSession input').keypress(function (e) {
            if (e.which == 13) {
                searchSession();
                return false; //<---- Add this line
            }
        });
    };

    /**
     * 获取聊天详情
     * @param obj
     */
    var findSessionInfo = function (obj,read) {
        if(read!=0){read=1;}
        var sessionKey=$(obj).attr('sessionKey');
        var userId=$(obj).attr('userId');
        $.ajax({
            url:'/user-message/message-list',
            type:'post',
            data:{
                sessionKey:sessionKey,
                userId:userId,
                read:read
            },
            success:function(data){
                var datas= $.parseJSON(data);
                if(datas.status==1){
                    var liUserId=$(sessionList).find("li[class='active']").eq(0).attr('userId');
                    var senderImg=$(sessionList).find("li[class='active']").find('img').eq(0).attr('src');
                    var receiveImg=$(sessionList).find("li[class='active']").find('img').eq(1).attr('src');
                    var senderName=$(sessionList).find("li[class='active']").find("span[class='nickname']").eq(0).text();
                    var receiveName=$(sessionList).find("li[class='active']").find("span[class='nickname']").eq(1).text();

                    $('#messageListUl').html('');
                    $('.nicknameTitle').html(senderName+'  -  '+receiveName+'   对话中');
                    $('#messageListTmpl').tmpl(datas.data,{
                        getType:function(userId){if(liUserId!=userId){return 'in';}else{return 'out';}},
                        getImg:function(userId){if(liUserId!=userId){return senderImg;}else{return receiveImg;}},
                        getName:function(userId){if(liUserId!=userId){return senderName;}else{return receiveName;}}
                    }).appendTo('#messageListUl');
                    cont.find('.scroller').slimScroll({
                        scrollTo: getLastPostPos()
                    });
                    $("#chats").show();
                    if(read==1){
                        var count= $(sessionList).find("li[class='active']").find("div[class='tip']").find("span").html();
                        count=count==''?0:parseInt(count);
                        $(sessionList).find("li[class='active']").find("div[class='tip']").find("span").html("");

                        var messageTip=$("#header_message_box a span");
                        var oldCount=$(messageTip).text()==''?0:parseInt($(messageTip).text());
                        var nowCount=oldCount-count;
                        nowCount=nowCount==0?'':nowCount;
                        $(messageTip).html(nowCount);

                    }
                }
            }
        });
    };

    /**
     * 获取聊天会话列表
     * @param keywords
     */
    var getMessageSessionList=function(keywords){
        $.ajax({
            url:'/user-message/message-session-list',
            type:'post',
            data:{
                keywords:keywords
            },
            beforeSend:function(){
                Main.showWait();
            },
            error:function(){
                Main.errorTip("获取用户会话列表失败");
                Main.hideWait();
            },
            success:function(data){
                Main.hideWait();
                var datas= $.parseJSON(data);
                if(datas.status==1){
                    $("#userSessionListUl").html("")
                    $("#chats").hide();
                    $('#sessionListTmpl').tmpl(datas.data,{
                        getName:function(name){return name;},
                        getUnReadCount:function(count){count=count==0?'':count;return count;}
                    }).appendTo('#userSessionListUl');
                    $(sessionList).find('li').off('click').on('click',function(){
                        $(sessionList).find('li').removeClass('active');
                        $(this).addClass('active');
                        findSessionInfo(this,1);
                    });
                    $(sessionList).find('li').off('mouseover').on('mouseover',function(){
                        $(this).find('b').show();
                    });
                    $(sessionList).find('li').off('mouseout').on('mouseout',function(){
                        $(this).find('b').hide();
                    });
                    $(sessionList).find("li:first").addClass('active');
                    findSessionInfo($(sessionList).find("li:first"),0);

                }else{
                    Main.errorTip("获取用户会话列表失败:"+datas.data);
                }
            }
        });
    };


    $(document).ready(function () {
        $('.scroller').slimScroll({});
        getMessageSessionList();
        initDefaultBtnClick();
    });




</script>