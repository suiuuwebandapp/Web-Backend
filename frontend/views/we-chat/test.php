<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.4.2.min.js"></script>
<input type="text" id="txt" />
<div id="dv"></div>
<script>
    var timer, windowInnerHeight;
    function eventCheck(e) {
        alert(window.innerHeight);
        if (e) { //blur,focus事件触发的
            $('#dv').html('android键盘' + (e.type == 'focus' ? '弹出' : '隐藏') + '--通过' + e.type + '事件');
            if (e.type == 'click') {//如果是点击事件启动计时器监控是否点击了键盘上的隐藏键盘按钮，没有点击这个按钮的事件可用，keydown中也获取不到keyCode值
                setTimeout(function () {//由于键盘弹出是有动画效果的，要获取完全弹出的窗口高度，使用了计时器
                    windowInnerHeight = window.innerHeight;//获取弹出android软键盘后的窗口高度
                    timer = setInterval(function () { eventCheck() }, 100);
                }, 500);
            }
            else clearInterval(timer);
        }
        else { //计时器执行的，需要判断窗口可视高度，如果改变说明android键盘隐藏了
            if (window.innerHeight > windowInnerHeight) {
                clearInterval(timer);
                $('#dv').html('android键盘隐藏--通过点击键盘隐藏按钮');
            }
        }
    }
    $('#txt').click(eventCheck).blur(eventCheck);
</script>