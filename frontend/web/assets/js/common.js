// JavaScript Document




/*-----checkUI-----*/

$(function(){
		//$('input[type=checkbox]').prop('checked','')
		$('input[type=checkbox]').click(function(e) {
			if( $(this).prop("checked")){
				$(this).next('label').css('background-position','0 -157px')

			}else{
				$(this).next('label').css('background-position','0 -102px')
			}

        });
})


/*-----Radio--UI-----*/

$(function(){
		//$('input[type=radio]').prop('checked','')
		$('input[type=radio]').click(function(e) {
			if( $(this).prop("checked")){
				$(this).next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')

			}else{
				$(this).next('label').css('background-position','0 10px')
			}

        });
})




/*----详情页滚动中间导航效果----*/

/*
$(function(){
	$(window).scroll(function(e) {
		var h1=$(window).scrollTop();
		if(h1>400){
			$('.sydetailNav').addClass('fixed')
			$('.sydetail .web-right').addClass('fixed')
		}else{
			$('.sydetailNav').removeClass('fixed')
			$('.sydetail .web-right').removeClass('fixed')
		}

    });

})
*/
/*----帮助中心-导航---*/


$(function(){
	$(window).scroll(function(e) {
		var h1=$(window).scrollTop();
		if(h1>20){
			$('#aboutCon-out .aboutNav-out').addClass('fixed')
			$('#aboutCon-out .leftNav').addClass('fixed')
		}else{
			$('#aboutCon-out .aboutNav-out').removeClass('fixed')
			$('#aboutCon-out .leftNav').removeClass('fixed')
		}

    });

	$('#aboutCon-out .aboutCon .leftNav li .drop02 a').click(function(e) {
			$(this).addClass('active').siblings().removeClass('active');



    });

})
/*----首页广告---*/


$(function(){
	 var appDownloadClosed = false;
    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= 300) {
            if (!appDownloadClosed) {
                $(".indexAd").show();
            }
        } else {
            $(".indexAd").hide();
        }
    });

    $(".indexAd .closed").on("click", function () {
        $(".indexAd").hide();
        appDownloadClosed = true;
    });
});



/*----nav-index登录、注册弹框-----*/

$(function(){
	$('.nav-out .nav-right ol .zhuces #zhuce').click(function(e) {
        $('.myLogins .a1').addClass('active');
        $('.myLogins .a2,.myLogins .a3').removeClass('active');
        $('.mask,.myLogins').toggle();
        $('.myLogins .box1').css('display','block').siblings().css('display','none');

    });
	$('.nav-out .nav-right ol .logins #denglu').click(function(e) {
        $('.myLogins .a2').addClass('active');
        $('.myLogins .a1,.myLogins .a3').removeClass('active');
        $('.mask,.myLogins').toggle();
        $('.myLogins .box2').css('display','block').siblings().css('display','none');
    });

})


/*-----header弹框效果-----*/
$(function(){
	$('.header-right .xitong>a').click(function(e) {
        $('.xit-sz').toggle();
        $('.header-right .name').children('.my-suiuu').css('display','none');
    });

	$('.header-right .name>a').click(function(e) {
        $('.my-suiuu').toggle();
        $('.header-right .xitong').children('.xit-sz').css('display','none');
    });



})

/*-----随游详情页banner轮播----*/
$(function(){
    var maxWidth=0;
    var beforeWidth=0;
    var endWidth=0;
    var area=$('.sydetailBanner .banner ul');
    var html=$(area).html();
    var timer=null;
    var documentWidth=$(document).width();
    var left=0;
    if($('.sydetailBanner .banner ul').size()==0){
        return;
    }
    var loadImgTimer=window.setInterval(function(){
        var loadSuccess=true;
        maxWidth=0;
        $('.sydetailBanner .banner li img').each(function(){
            if($(this).width()==0){
                loadSuccess=false;
            }
             maxWidth+=$(this).width();
            beforeWidth=maxWidth;
        });
        if(loadSuccess){
            window.clearInterval(loadImgTimer);
            var flag=true;
            while(flag){
                if((maxWidth<documentWidth)){
                    maxWidth+=beforeWidth;
                    $(area).width(maxWidth);
                    $(area).html($(area).html()+html);
                }else{
                    flag=false;
                }
            }
            endWidth=documentWidth-maxWidth;
            function fn(){
                if(left==endWidth){left=0;}else{left-=500;}
                if(left>0){left=0;}
                if(left<endWidth&&left!=0){left=endWidth;}
                $('.web-banner .banner').stop().animate({left:left},1000)
            }
            timer=setInterval(fn,2000)
            $('.web-banner ul li').hover(function(e) {
                clearInterval(timer)

            },function(){
                clearInterval(timer)
                timer=setInterval(fn,2000)

            });

            $('.web-banner .next').click(function(e) {

                if(left==endWidth){left=0;}else{left-=500;}
                if(left>0){left=0;}
                if(left<endWidth&&left!=0){left=endWidth;}
                $('.web-banner .banner').stop().animate({left:left},500)

            });
            $('.web-banner .pre').click(function(e) {
                if(left==0){left=endWidth}else{left+=500;}
                if(left>0){left=0;}
                if(left<endWidth&&left!=0){left=endWidth;}
                $('.web-banner .banner').stop().animate({left:left},500)
            });
            $('.web-banner .nex,.web-banner .pre').hover(function(e) {
                clearInterval(timer)
            });

            $('.web-banner .prev,.web-banner .next').hover(function(e) {
                clearInterval(timer)
            },function(){
                clearInterval(timer)
                timer=setInterval(fn,2000)
            });
        }
    },1000);
})


/*-----专栏qq分享-----*/
$(function(){
	$('#fenxiang').click(function(e) {
        $('#other-line').toggle()
    });

})




/*-----随友个人中心私信对话框-----*/
$(function(){
	$('.sycon .myEmail .emailCon .left ul li').click(function(e) {
        $('.sycon .myEmail .emailCon .right').toggle();
    });

})


/*-----随游 select---*/
$(function(){
	$('.sylx .containers .select .math').click(function(e) {
        $(this).children('.sel').toggle();
    });

})

/*-----随游-类型选择----*/
/*$(function(){
	$('.sylx .sylx-xiangxi .p2 span').click(function(e) {
		if($(this).hasClass('active')){
			 $(this).removeClass('active');
			}else{
			 $(this).addClass('active');
		}

    });

})
*/



/*-----随游Bnner推广弹窗----*/
$(function(){
	$('.sylx .syBanner .detailBtn').click(function(e) {
		$('.mask,.sydetailPop').css('display','block')
    });

})


/*-----完成页满屏显示----*/
$(function(){
	var h1=$(window).height();
	var h3=$('#footer-out').height();
	var myh=h1-h3-40-110;
	$('#finish').outerHeight(myh+'px')
	$('.forgotPaw').outerHeight(myh+'px')
	$('.syRegisterT').outerHeight(myh+'px')


})
/*-----关闭浮层---*/
$(function(){
	var h=$(window).height();
	var w=$(window).width();
	$('.mask').height(h).width(w);
	$('.mask').click(function(e) {
        $(this).css('display','none')
        $('.screens').css('display','none')
    });


})


/*-----syhPro弹出浮层-----

$(function(){
	$('.suiyouHelp .btn').click(function(e) {
        $('.syhPro,.mask').css('display','block')
    });

})
 */



/*-----sy编辑step1提示-----*/
$(function(){
	$('.bjy-bj1 .name').hover(function(e) {
        $('.bjy-bj1 .bj1Pro01').css('display','block');

    },function(){
        $('.bjy-bj1 .bj1Pro01').css('display','none');
    });
	$('.bjy-bj1 .fPic').hover(function(e) {
        $('.bjy-bj1 .bj1Pro02').css('display','block');

    },function(){
        $('.bjy-bj1 .bj1Pro02').css('display','none');
    });


	$('.bjy-bj2 .jings').hover(function(e) {
        $('.bjy-bj2 .bj2Pro01').css('display','block');

    },function(){
        $('.bjy-bj2 .bj2Pro01').css('display','none');
    });


	$('.bjy-bj4 .price1').hover(function(e) {
        $('.bjy-bj4 .bj4Pro01').css('display','block');

    },function(){
        $('.bjy-bj4 .bj4Pro01').css('display','none');
    });

	$('.bjy-bj4 .price2').hover(function(e) {
        $('.bjy-bj4 .bj4Pro02').css('display','block');

    },function(){
        $('.bjy-bj4 .bj4Pro02').css('display','none');
    });

	$('.bjy-bj4 .creat').hover(function(e) {
        $('.bjy-bj4 .bj4Pro03').css('display','block');

    },function(){
        $('.bjy-bj4 .bj4Pro03').css('display','none');
    });

	$('.bjy-bj4 .start-time').hover(function(e) {
        $('.bjy-bj4 .bj4Pro04').css('display','block');

    },function(){
        $('.bjy-bj4 .bj4Pro04').css('display','none');
    });

	$('.bjy-bj5 .box01').hover(function(e) {
        $('.bjy-bj5 .bj5Pro01').css('display','block');

    },function(){
        $('.bjy-bj5 .bj5Pro01').css('display','none');
    });

	$('.bjy-bj5 .box02').hover(function(e) {
        $('.bjy-bj5 .bj5Pro02').css('display','block');

    },function(){
        $('.bjy-bj5 .bj5Pro02').css('display','none');
    });

	$('.bjy-bj5 .box03').hover(function(e) {
        $('.bjy-bj5 .bj5Pro03').css('display','block');

    },function(){
        $('.bjy-bj5 .bj5Pro03').css('display','none');
    });


	$('.bjy-bj5 .biaoqian ul li.add').click(function(e) {
        $('.bjy-bj5 .bj5Add,.mask').css('display','block');
    });
	$('.bjy-bj5 .biaoqian a.addL').click(function(e) {
        $('.bjy-bj5 .tog').slideToggle();
    });




   /*-----交通编辑提示-----*/

	$('.bjy-bj3 .car').hover(function(e) {
        $('.bjy-bj3 .bj3Pro01').css('display','block');

    },function(){
        $('.bjy-bj3 .bj3Pro01').css('display','none');
    });

	$('.bjy-bj3 .pack').hover(function(e) {
        $('.bjy-bj3 .bj3Pro02').css('display','block');

    },function(){
        $('.bjy-bj3 .bj3Pro02').css('display','none');
    });

	$('.bjy-bj3 .carPic').hover(function(e) {
        $('.bjy-bj3 .bj3Pro03').css('display','block');

    },function(){
        $('.bjy-bj3 .bj3Pro03').css('display','none');
    });


})

/*-----sy资料补全页name提示-----*/

$(function(){
	$('.syInformation .forms .name').hover(function(e) {
        $('.syInformation .forms .name .nameTip').css('display','block');

    },function(){
        $('.syInformation .forms .name .nameTip').css('display','none');
    });

})
/*-----个人中心-----*/

$(function(){
	$('.sycon .myInformation .past01 .box .tog .adds').click(function(e) {
		$(this).parents().children('.togT').toggle();

    })

})

/*-----帮助中心-----*/
$(function(){
	$('#aboutCon-out .helps .feedBack .choose .sel').click(function(e) {
		if( $('#aboutCon-out .helps .feedBack .choose .sel #rad02').prop("checked")){
			$('#aboutCon-out .helps .feedBack .choose .drop').css('display','block');
		}else{
			$('#aboutCon-out .helps .feedBack .choose .drop').css('display','none');
		}
	});
})
/*-----帮助中心侧导航 三级下拉菜单-----*/
$(function(){
	$('#aboutCon-out .aboutCon .leftNav li>a').click(function(e) {
		if( $('#aboutCon-out .aboutCon .leftNav li>a').hasClass('active')){
			$('#aboutCon-out .aboutCon .leftNav li .drop02').css('display','none');
			$(this).parent().children('.drop02').css('display','block');
		}else{
			$(this).parent().children('.drop02').css('display','none');
		}
	});
})


























