<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0032)http://localhost:8080/jsandroid/ -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-store,no-cache">
    <meta name="Handheldfriendly" content="true">
    <meta name="viewport" content="width=100%; initial-scale=1.0; user-scalable=yes">
    <meta name="robots" content="all">
    <meta name="keywords" contect="doodle, mobile, doodlemobile, game, games">
    <meta name="description" content="Make People's Mobile Life More Connected Through Games.">

    <title>jsandroid_test</title>

    <script type="text/javascript" language="javascript">

        function showHtmlcallJava(){
            var str = window.jsObj.HtmlcallJava();
            alert(str);
        }

        function showHtmlcallJava2(){
            var str = window.jsObj.HtmlcallJava2("IT-homer blog");
            alert(str);
        }

        function showFromHtml(){
            document.getElementById("id_input").value = "Java call Html";
        }

        function showFromHtml2( param ){
            document.getElementById("id_input2").value = "Java call Html : " + param;
        }
    </script>
</head>


<body>

hello IT-homer

<br>
<br>
<br>

<input type="button" value="HtmlcallJava" onclick="showHtmlcallJava()" />
<br>
<input type="button" value="HtmlcallJava2" onclick="showHtmlcallJava2()" />

<br>
<br>
<br>
<br>

<input id="id_input" style="width: 90%" type="text" value="null" />
<br>
<input type="button" value="JavacallHtml" onclick="window.jsObj.JavacallHtml()" />

<br>
<br>
<br>

<input id="id_input2" style="width: 90%" type="text" value="null" />
<br>
<input type="button" value="JavacallHtml2" onclick="window.jsObj.JavacallHtml2()" />

</body>
</html>

