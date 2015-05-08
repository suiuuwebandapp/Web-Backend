<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>专栏</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <script src="/assets/js/move.js"></script>
    <script>
        function findInArr(arr,n){
            for(var i = 0; i < arr.length; i++){
                if(arr[i] == n){
                    return true;
                }
            }
            return false;
        }
        function getByClass(oParent,sClass){
            var aEle = oParent.getElementsByTagName("*");
            var ret = [];
            for(var i = 0; i < aEle.length; i++){

                var aTmp = aEle[i].className.split(" ");//["box","fl"]
                if(findInArr(aTmp,sClass) ){
                    ret.push(aEle[i]);
                }
            }
            return ret;
        }
        window.onload=function(){
            /*zl tanchu*/
            var oImg=document.getElementById('img');
            var oTan=document.getElementById('tanchu-main');
            var oMask=document.getElementById('mask');
            var wHeight=document.documentElement.clientHeight||document.body.clientHeight;
            var hWidth=document.documentElement.clientWidth||document.body.clientWidth;
            oMask.style.width=hWidth+'px';
            oMask.style.height=wHeight+'px';
            oMask.onclick=function(){
                this.style.display="none";
                oTan.style.display="none";

            };
            /*zl tanchu*/
        };

    </script>
    <style type="text/css">
        $("body").css("background","#eeeeee");
    </style>
</head>


<div class="mask" id="mask"></div>
<!--tanchu Begin-->
<div class="tanchu-main" id="tanchu-main">
    <div id="content_t">


    <input hidden="hidden" id="content_tc">
    </div>
    <div id="pllist" class="web-bar">
        <ol>
            <li><a href="#pinglun">评论</a></li>
            <li></li>
            <li id="fenxiang"><a href="###">分享</a>
                <div id="other-line">
                    <a href="#" class="icon sina"></a><a href="#" class="icon wei"></a><a href="#" class="icon qq"></a>

                </div>
            </li>
        </ol>


    </div>
    <div class="zhuanlan-web">
        <ul id="tanchu_pl">


        </ul>
        <ol id="spage">
        </ol>
    </div>
    <div class="zhuanlan-text">

        <textarea id="pinglun"></textarea>
        <a href="javascript:;" class="zl-btn" onclick="submitComment()">发表评论</a>
    </div>





</div>
<!--tanchu End-->
<!--zl Begin-->
<div class="zl-banner clearfix" id="img" onclick="showOld(<?php $id=isset($onList['articleId'])?$onList['articleId']:0;echo $id; ?>)">
    <div class="con w1200 clearfix">
        <h3 class="data"><?php if(empty($onList)){echo '暂无最新专栏';}else{$str=isset($onList['title'])?$onList['title']:'';echo $str;}?></h3>
        <h4 class="tit"><?php if(empty($onList)){echo '暂无最新专栏';}else{$str=isset($onList['name'])?$onList['name']:'';echo $str;}?></h4>
    </div>
</div>
<!--zl END-->
<div class="tanchu-list w1200"><!--tanchu-list Begin-->
    <ul>
        <?php foreach($oldList as $val){?>
        <li onclick="showOld(<?php $id=isset($val['articleId'])?$val['articleId']:0;echo $id; ?>)"> <img src="<?php echo $val['titleImg']?>" alt="">
            <div>
                <h4><?php echo $val['title']?></h4>
                <p><?php echo $val['name']?></p>
            </div>
        </li>
        <?php }?>
    </ul>
</div>


<script>
var rid=0;
var articleId=0;
var page=1;
var rSign='';
    function showOld(id)
    {
        articleId=id;
        $.ajax({
            type: 'post',
            url: '/article/get-article-info',
            data: {
                id: id,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {
                    $('#content_t').html('');
                    $('#content_t').append(obj.data.content);

                    var oImg=document.getElementById('img');
                    var oMask=document.getElementById('mask');
                    var oTan=document.getElementById('tanchu-main');
                    oMask.style.display="block";
                    oTan.style.display="block";
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
        getComment(1);
    }
function getComment(page)
{
    $.ajax({
        type: 'post',
        url: '/article/get-article-comment',
        data: {
            id: articleId,
            page:page,
            _csrf: $('input[name="_csrf"]').val()
        },
        beforeSend: function () {
            //Main.showTip('正在提交，请稍后。。。');
        },
        error:function(){
            Main.showTip("系统异常。。。");
        },
        success: function (data) {
            var obj=eval('('+data+')');
            if(obj.status==1)
            {
                $('#tanchu_pl').html('');
                var str='';
                var l =obj.data.comment.length;
                if(l==0)
                {
                    str='<li><p>tripId</p></li>';
                }
                for(var i=0;i<l;i++)
                {
                    var r=obj.data.comment[i].rTitle;
                    if(r==null)
                    {
                        r='';
                    }
                    var c='';
                    var status=obj.data.comment[i].status;
                    if(status==1)
                    {
                        c='active'
                    }
                    str+='<li>';
                    str+='<div class="user-pic fl">';
                    str+='<img src=\"'+obj.data.comment[i].headImg+'\" alt=\"\">';
                    str+='<span class=\"user-name\">';
                    str+=obj.data.comment[i].nickname;
                    str+="</span></div><p class='fl'><b>";
                    str+=r;
                    str+="</b>";
                    str+=' '+obj.data.comment[i].content;
                    str+="</p><div class='fr resp'><a href='javascript:;' onclick='sumbmitZan("+obj.data.comment[i].commentId+","+"this)' class='picon zan "+c+"'></a><a href='#pllist'  rSign='"+obj.data.comment[i].userSign+"'  id='"+obj.data.comment[i].commentId+"' class='picon huifu' onclick='reply(this)'></a>";
                    str+="</div></li>";
                }
                $('#tanchu_pl').append(str);

                $('#spage').html('');
                $('#spage').append(obj.message);

                $("#spage li").click(function() {
                   var page=$(this).find('a').attr('page');
                    getComment(page);
                });

            }else
            {
                Main.showTip(obj.data);

            }
        }
    });
}
    function reply(obj)
    {
        rid=$(obj).attr('id');
        rSign=$(obj).attr('rSign');
        var t=$(obj).parent("div").prev().prev().find("span").html();
        $("#pinglun").val('@'+t+'   :');

    }

    function submitComment()
    {
        var s=$('#pinglun').val();
        var i =s.indexOf(':');
        if(i==-1){
            var content= s;
            var t='';
        }else
        {

            var t=s.slice(0,i);
            var content= s.slice(i);
        }
        $.ajax({
            type: 'post',
            url: '/article/add-article-comment',
            data: {
                articleId: articleId,
                content: content,
                rTitle: t,
                rId: rid,
                rSign:rSign,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {
                    //Main.showTip("发表成功。。。");
                    getComment(page);
                    $("#pinglun").val('');
                }else
                {
                    Main.showTip(obj.data);

                }
            }
        });
    }

    function sumbmitZan(id,obj)
    {
        var s =$(obj).attr('class');
        var i =s.indexOf('active');
        if(i!=-1){
            Main.showTip('已经点赞');
            return;
        }
        $(obj).attr('class','picon zan active');
        $.ajax({
            type: 'post',
            url: '/article/add-support',
            data: {
                articleId: articleId,
                rId: id,
                _csrf: $('input[name="_csrf"]').val()
            },
            beforeSend: function () {
                //Main.showTip('正在提交，请稍后。。。');
            },
            error:function(){
                Main.showTip("系统异常。。。");
            },
            success: function (data) {
                var obj=eval('('+data+')');
                if(obj.status==1)
                {

                    //Main.showTip("发表成功。。。");
                    getComment(page);
                }else
                {
                    Main.showTip(obj.data);
                    $(obj).attr('class','picon zan');

                }
            }
        });
    }
</script>