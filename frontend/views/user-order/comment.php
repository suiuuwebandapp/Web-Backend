<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/20
 * Time : 下午5:50
 * Email: zhangxinmailvip@foxmail.com
 */

?>

<style type="text/css">


</style>

<input type="hidden" id="orderId" value="<?=$orderId?>"/>

<div class="makeScores clearfix">
    <div class="left">
        <h2 class="title">评分及评价</h2>
        <h3 class="title02">关于服务</h3>
        <p>随友对路线是否熟悉？</p>
        <div class="xing clearfix">
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star01"></span>
        </div>
        <p>随友是否在行程中专注为您提供服务？</p>
        <div class="xing clearfix">
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star01"></span>
        </div>
        <p>随友是否守时？</p>
        <div class="xing clearfix">
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star01"></span>
        </div>
        <h3 class="title02">关于随游</h3>
        <p>该随游路线/活动的设计是否合理？</p>
        <div class="xing clearfix">
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star02"></span>
            <span class="star01"></span>
        </div>
        <h3 class="title02">谈谈您的体验</h3>
        <p>您的体验将会显示在相应的随游详情页及相关随友的个人主页上</p>
        <textarea id="content" placeholder="与随友一起游玩体验如何？"></textarea>
        <h3 class="title02">其他反馈</h3>
        <p>是否有任何其他反馈提供给我们？该信息不会公开显示</p>
        <textarea id="otherContent" placeholder="选填"></textarea>
        <a href="javascript:;" onclick="addComment()" class="btn bgGreen">提交评论</a>





    </div>
    <div class="right">
        <h2 class="title bgGreen"><?=$tripInfo->title?></h2>
        <a href="<?=\common\components\SiteUrl::getTripUrl($tripInfo->tripId);?>" target="_blank" class="product"><img src="<?=$tripInfo->titleImg?>"></a>
        <p><?=date("Y年m月d日",strtotime($orderInfo->beginDate))?></p>
        <div class="down">
            <span>与</span>
            <a href="<?=\common\components\SiteUrl::getViewUserUrl($publisherUserInfo->userSign)?>" target="_blank" class="user">
                <img src="<?=$publisherUserInfo->headImg?>">
            </a>
            <span><?=$publisherUserInfo->nickname?></span>
            <span>一起体验了<?=$tripInfo->title?></span>
        </div>
    </div>

</div>




<script type="text/javascript">
    var nowStar=0;
    $(document).ready(function(){
        selectStar();
    });

    function selectStar(){
        $(".xing span").bind("click",function(){
            var p=$(this).parent();
            var index=p.find("span").index(this);
            nowStar=index;
            //当前变亮
            $(this).removeClass("star01");
            $(this).addClass("star02");
            //小于当前变亮
            p.find("span:lt("+index+")").removeClass("star01");
            p.find("span:lt("+index+")").addClass("star02");
            //大于当前变灰
            p.find("span:gt("+index+")").removeClass("star02");
            p.find("span:gt("+index+")").addClass("star01");
        });
        $(".xing span").bind("mouseover",function(){
            var p=$(this).parent();
            var index=p.find("span").index(this);
            //当前变亮
            $(this).removeClass("star01");
            $(this).addClass("star02");
            //小于当前变亮
            p.find("span:lt("+index+")").removeClass("star01");
            p.find("span:lt("+index+")").addClass("star02");
            //大于当前变灰
            p.find("span:gt("+index+")").removeClass("star02");
            p.find("span:gt("+index+")").addClass("star01");

        });
        $(".xing span").bind("mouseout",function(){
            var p=$(this).parent();
            var index=nowStar;
            //当前变亮
            p.find("span").eq(index).removeClass("star01");
            p.find("span").eq(index).addClass("star02");
            //小于当前变亮
            p.find("span:lt("+index+")").removeClass("star01");
            p.find("span:lt("+index+")").addClass("star02");
            //大于当前变灰
            p.find("span:gt("+index+")").removeClass("star02");
            p.find("span:gt("+index+")").addClass("star01");
        });
    }

    function addComment(){
        var orderId=$("#orderId").val();
        var content=$("#content").val();
        var otherContent=$("#otherContent").val();
        var tripScore=0;
        var familiarScore=0;
        var absorbedScore=0;
        var punctualScore=0;
        $(".xing").eq(0).find("span").each(function(){
            if($(this).hasClass("star02")){
                familiarScore=familiarScore+2;
            }else{
                return true;
            }
        });
        $(".xing").eq(1).find("span").each(function(){
            if($(this).hasClass("star02")){
                absorbedScore=absorbedScore+2;
            }else{
                return true;
            }
        });
        $(".xing").eq(2).find("span").each(function(){
            if($(this).hasClass("star02")){
                punctualScore=punctualScore+2;
            }else{
                return true;
            }
        });
        $(".xing").eq(3).find("span").each(function(){
            if($(this).hasClass("star02")){
                tripScore=tripScore+2;
            }else{
                return true;
            }
        });

        if(content==''){
            Main.showTip("请输入评论内容");
            return;
        }

        $.ajax({
            url :'/user-order/add-comment',
            type:'post',
            data:{
                orderId:orderId,
                content:content,
                otherContent:otherContent,
                tripScore:tripScore,
                familiarScore:familiarScore,
                absorbedScore:absorbedScore,
                punctualScore:punctualScore,
                _csrf: $('input[name="_csrf"]').val()
            },
            error:function(){
                Main.showTip("添加评论失败");
            },
            success:function(data){
                var datas=eval('('+data+')');
                if(datas.status==1){
                    Main.showTip("添加评论成功");
                    window.location.href="/user-info?tab=myOrderManager";
                }else{
                    Main.showTip("添加评论失败");
                }
            }
        });

    }

</script>