/**
 * Created by suiuu on 15/9/7.
 */
/*** scoket connec***/
if (typeof console == "undefined") {
    this.console = {
        log: function (msg) {
        }
    };
}
WEB_SOCKET_DEBUG = true;
var ws, name, client_list = {}, timeid, reconnect = false;

function initSocketConnection() {

    // 创建websocket
    ws = new WebSocket("ws://58.96.191.44:7272");
    // 当socket连接打开时，输入用户名
    ws.onopen = function () {
        if(reconnect == false) {
            // 登录
            var login_data = JSON.stringify({"type":"login","user_key":sessionId});
            console.log("socket connection success");
            ws.send(login_data);
            reconnect = true;
        }else{
            // 断线重连
            var relogin_data = JSON.stringify({"type":"re_login","user_key":sessionId});
            console.log("socket reconnection success");
            ws.send(relogin_data);
        }
    };
    // 当有消息时根据消息类型显示不同信息
    ws.onmessage = function (e) {
        console.log(e.data);
        var data = JSON.parse(e.data);
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send(JSON.stringify({"type":"pong"}));
                break;;
            // 登录 更新用户列表
            case 'login':
                console.log(data['client_name']+"登录成功");
                break;
            // 断线重连，只更新用户列表
            case 're_login':
                console.log(data['client_name']+"重连成功");
                break;
            // 发言
            case 'say':
                //console.log(data);
                //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                newMessage(data);
                break;
            // 用户退出 更新用户列表
            case 'logout':
                console.log("用户退出了登录");

        }

    };
    ws.onclose = function () {
        console.log("连接关闭，定时重连");

    };
    ws.onerror = function () {
        console.log("出现错误");
    };
}
initSocketConnection();
function wxSendMessage(rId,userHeadImg)
{
    var info =$("#wxSendMessage").val();
    if(info=="")
    {
        return;
    }
var str = '<li class="you clearfix">';
    str+='<img src="' ;
    str+= userHeadImg;
    str+='" class="pic">';
    str+='<p class="p1">';
    str+=info;
    str+='</p>';
    str+='</li>';
    $("#messageUl").append(str);
    ws.send(JSON.stringify({"type": "say","to_client_id": rId,"content":info}));
    $("#wxSendMessage").val("");
    changeHight();
}
function changeHight(){
    $("#messageDiv").scrollTop($("#messageDiv ul")[0].scrollHeight);

}