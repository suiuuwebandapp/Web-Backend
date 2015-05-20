<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
    <title>国家</title>
    <link rel="stylesheet" href="/assets/other/weixin/css/common.css">
    <link rel="stylesheet" href="/assets/other/weixin/css/weixin.css">
    <script type="text/javascript" src="/assets/other/weixin/js/jquery-1.11.1.min.js"></script>
</head>

<body>
<nav>国家</nav>
<div class="con country clearfix" id="country_list_all">
    <div class="navbars">
        <ul class="check">
            <li><a href="#A">A</a></li>
            <li><a href="#B">B</a></li>
            <li><a href="#C">C</a></li>
            <li><a href="#D">D</a></li>
            <li><a href="#E">E</a></li>
            <li><a href="#F">F</a></li>
            <li><a href="#G">G</a></li>
            <li><a href="#H">H</a></li>
            <li><a href="#I">I</a></li>
            <li><a href="#G">G</a></li>
            <li><a href="#K">K</a></li>
            <li><a href="#L">L</a></li>
            <li><a href="#M">M</a></li>
            <li><a href="#N">N</a></li>
            <li><a href="#O">O</a></li>
            <li><a href="#P">P</a></li>
            <li><a href="#Q">Q</a></li>
            <li><a href="#R">R</a></li>
            <li><a href="#S">S</a></li>
            <li><a href="#T">T</a></li>
            <li><a href="#U">U</a></li>
            <li><a href="#V">V</a></li>
            <li><a href="#W">W</a></li>
            <li><a href="#X">X</a></li>
            <li><a href="#Y">Y</a></li>
            <li><a href="#Z">Z</a></li>
        </ul>
    </div>
    <input id="country_search" type="search" class="search" placeholder="搜索">
    <?php
    $common = new \common\components\Common();
    $countryArr=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    foreach($countryArr as $cod){
        ?>
    <dl class="lists">
        <dt id="<?php echo $cod;?>"><?php echo $cod;?></dt>
        <?php
        foreach($list as $val){
            $str =$common->getInitials($val['cname']);
            if($str[0]==$cod){
            ?>
        <dd onclick="toRegister(this)" areaCode="<?php echo $val['areaCode']?>"><?php echo $val['cname']?></dd>
        <?php
            }
        }?>
    </dl>
    <?php
    }?>
</div>
<script>
    $("#country_search").keyup(function(){
       var val=$("#country_search").val();
        if(val!='')
        {
            $('#country_list_all').find('dt').css('display','none');
            $('#country_list_all').find('dd').each(function(){
                if($(this).html().indexOf(val)!=-1){
                $(this).show();
                }else
                {
                    $(this).hide();
                }
            });
        }else
        {
            $('#country_list_all').find('dt').css('display','block');
            $('#country_list_all').find('dd').each(function(){
                $(this).show();
            });
        }
    });

    function toRegister(obj)
    {
        var code = $(obj).attr('areaCode');
        var name =  $(obj).html();
        window.location.href='/we-chat/register?c='+code+"&n="+name;
    }

</script>
</body>
</html>
