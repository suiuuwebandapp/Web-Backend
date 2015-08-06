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
    .star{
        cursor: pointer;
        width: 15px;
        height: 15px;
        display: inline-block;
    }

</style>

<input type="hidden" id="orderId" value="<?=$orderId?>"/>
<div class="pjy-main w1200 clearfix">
    <div class="pjy-content clearfix">
        <div class="pjy-left">
            <h3><?=$tripInfo->title?></h3>
            <div class="pic"><img src="<?=$tripInfo->titleImg?>" alt=""></div>
            <p><?=str_replace("\n","<br>",$tripInfo->info)?></p>
            <span>基本价格<b><?=$tripInfo->basePrice?></b></span>
        </div>
        <div class="pjy-right">
            <div class="user">
                <div class="user-name">
                    <a href="<?=\common\components\SiteUrl::getViewUserUrl($publisherUserInfo->userSing)?>" target="_blank"><img class="user-pic" alt="" src="<?=$publisherUserInfo->headImg?>"></a>
                    <span><?=$publisherUserInfo->nickname?></span>
                </div>
                <p><?=$publisherUserInfo->intro?></p>
            </div>
            <div class="pf">
                <ul>
                    <li class="fen">行程数:<b><?=$publisherInfo->tripCount?></b></li>
                    <li class="fen">随游数:<b><?=$publisherInfo->leadCount?></b></li>
                </ul>
            </div>


            <div class="pf">
                <ul>
                    <li class="fen">
                        <p class="xing">
                            随友评分:
                            <img class="star" width="13" height="13" src="/assets/images/start1.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                        </p>
                    </li>
                    <li class="fen">
                        <p class="xing">
                            随游评分:
                            <img class="star" width="13" height="13" src="/assets/images/start1.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                            <img class="star" width="13" height="13" src="/assets/images/start2.fw.png">
                        </p>
                    </li>
                </ul>
            </div>


            <textarea id="content"></textarea>
        </div>
    </div><!--pjy-content-->
    <a href="javascript:addComment();" class="btn">发表评论</a>
</div>


<script type="text/javascript">
    var light="/assets/images/start1.fw.png";
    var gray="/assets/images/start2.fw.png";
    var nowStar=0;
    $(document).ready(function(){
        selectStar();
    });

    function selectStar(){
        $(".star").bind("click",function(){
            var p=$(this).parent();
            var index=p.find("img").index(this);
            nowStar=index;
            $(this).attr("src",light);
            p.find("img:lt("+index+")").attr("src",light);
            p.find("img:gt("+index+")").attr("src",gray);
        });
        $(".star").bind("mouseover",function(){
            var p=$(this).parent();
            var index=p.find("img").index(this);
            $(this).attr("src",light);
            p.find("img:lt("+index+")").attr("src",light);
            p.find("img:gt("+index+")").attr("src",gray);
        });
        $(".star").bind("mouseout",function(){
            var p=$(this).parent();
            var index=nowStar;
            p.find("img").eq(index).attr("src",light);
            p.find("img:lt("+index+")").attr("src",light);
            p.find("img:gt("+index+")").attr("src",gray);
        });
    }

    function addComment(){
        var orderId=$("#orderId").val();
        var content=$("#content").val();
        var tripScore=0;
        var publisherScore=0;
        $(".xing").eq(0).find("img").each(function(){
            if($(this).attr("src")==light){
                tripScore=tripScore+2;
            }else{
                return true;
            }
        });
        $(".xing").eq(1).find("img").each(function(){
            if($(this).attr("src")==light){
                publisherScore=publisherScore+2;
            }else{
                return true;
            }
        });

        if(content==''){
            Main.showTip("请输入评论内容");
            return;
        }
        if(tripScore==0){
            Main.showTip("请给随游打分");
            return;
        }
        if(publisherScore==0){
            Main.showTip("请给随友打分");
            return;
        }

        $.ajax({
            url :'/user-order/add-comment',
            type:'post',
            data:{
                orderId:orderId,
                content:content,
                tripScore:tripScore,
                publisherScore:publisherScore,
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