<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>workerman-chat PHP聊天室 Websocket(HTLM5/Flash)+PHP多进程socket实时推送技术</title>
    <script type="text/javascript">
        //WebSocket = null;
    </script>
    <link href="/assets/chat/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/chat/css/style.css" rel="stylesheet">
    <!-- Include these three JS files: -->
    <script type="text/javascript" src="/assets/chat/js/swfobject.js"></script>
    <script type="text/javascript" src="/assets/chat/js/web_socket.js"></script>
    <script type="text/javascript" src="/assets/chat/js/json.js"></script>
    <script type="text/javascript" src="/assets/chat/js/jquery.min.js"></script>

    <script type="text/javascript">
        if (typeof console == "undefined") {
            this.console = {
                log: function (msg) {
                }
            };
        }
        WEB_SOCKET_SWF_LOCATION = "/assets/chat/swf/WebSocketMain.swf";
        WEB_SOCKET_DEBUG = true;
        var ws, name, client_list = {}, timeid, reconnect = false;
        var sessionId = '<?=session_id()?>';
        function init() {
            // 创建websocket
            ws = new WebSocket("ws://"+document.domain+":7272");
            // 当socket连接打开时，输入用户名
            ws.onopen = function () {
                if(reconnect == false) {
                    // 登录
                    var login_data = JSON.stringify({"type":"login","user_key":sessionId});

                    console.log("websocket握手成功，发送登录数据:"+login_data);
                    ws.send(login_data);
                    reconnect = true;
                }else{
                    // 断线重连
                    var relogin_data = JSON.stringify({"type":"re_login","user_key":sessionId});
                    console.log("websocket握手成功，发送重连数据:"+relogin_data);
                    ws.send(relogin_data);
                }
            };
            // 当有消息时根据消息类型显示不同信息
            ws.onmessage = function (e) {
                console.log(e.data);

            };
            ws.onclose = function () {
                alert("onclose");

                console.log("连接关闭，定时重连");

            };
            ws.onerror = function () {
                alert("onerror");

                console.log("出现错误");
            };
        }

        // 输入姓名
        function show_prompt() {
            name = prompt('输入你的名字：', '');
            if (!name || name == 'null') {
                alert("输入名字为空或者为'null'，请重新输入！");
                show_prompt();
            }
        }

        // 提交对话
        function onSubmit() {
            var input = document.getElementById("textarea");
            var to_client_id = $("#client_list option:selected").attr("value");
            var to_client_name = $("#client_list option:selected").text();
            ws.send(JSON.stringify({
                "type": "say",
                "to_client_id": to_client_id,
                "to_client_name": to_client_name,
                "content": input.value
            }));
            input.value = "";
            input.focus();
        }

        // 刷新用户列表框
        function flush_client_list(client_list) {
            var userlist_window = $("#userlist");
            var client_list_slelect = $("#client_list");
            userlist_window.empty();
            client_list_slelect.empty();
            userlist_window.append('<h4>在线用户</h4><ul>');
            client_list_slelect.append('<option value="all" id="cli_all">所有人</option>');
            for (var p in client_list) {
                userlist_window.append('<li id="' + client_list[p]['client_id'] + '">' + client_list[p]['client_name'] + '</li>');
                client_list_slelect.append('<option value="' + client_list[p]['client_id'] + '">' + client_list[p]['client_name'] + '</option>');
            }
            $("#client_list").val(select_client_id);
            userlist_window.append('</ul>');
        }

        // 发言
        function say(from_client_id, from_client_name, content, time) {
            $("#dialog").append('<div class="speech_item"><img src="http://lorempixel.com/38/38/?' + from_client_id + '" class="user_icon" /> ' + from_client_name + ' <br> ' + time + '<div style="clear:both;"></div><p class="triangle-isosceles top">' + content + '</p> </div>');
        }

        $(function () {
            select_client_id = 'all';
            $("#client_list").change(function () {
                select_client_id = $("#client_list option:selected").attr("value");
            });
        });
    </script>
</head>
<body>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        init();
    });

</script>
