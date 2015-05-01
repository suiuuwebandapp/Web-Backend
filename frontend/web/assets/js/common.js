// JavaScript Document




/*-----登录注册checkUI-----*/

$(function(){
		$('input[type=checkbox]').prop('checked','')
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
		$('input[type=radio]').prop('checked','')
		$('input[type=radio]').click(function(e) {
			if( $(this).prop("checked")){
				$(this).next('label').css('background-position','0 -47px').siblings('label').css('background-position','0 10px')
				
			}else{
				$(this).next('label').css('background-position','0 10px')
			}
			
        });
})


/*----导航效果----*/


$(function(){
	$(window).scroll(function(e) {
		var h1=$(window).scrollTop();
		if(h1>10){
			$('.nav-out,.header').css('box-shadow','1px 3px 5px rgba(0,0,0,0.6)')
		}else{
			$('.nav-out,.header').css('box-shadow','none')
		}
		
		
        
    });
	


})

/*----nav-index登录、注册弹框-----*/


$(function(){
	$('.nav-out .nav-right ol li:eq(1)>a').click(function(e) {
        $('#zhuce-main').toggle();
        $('.nav-out .nav-right ol li:eq(2)').children('#denglu-main').css('display','none');
        $('#zhuce-main02').css('display','none');
    });
	$('.nav-out .nav-right ol li:eq(2)>a').click(function(e) {
        $('#denglu-main').toggle();
        $('.nav-out .nav-right ol li:eq(1)').children('#zhuce-main,#zhuce-main02').css('display','none');
		
    });
	


})



/*$(function(){
	$('.nav-out .nav-right ol li:eq(1)').hover(function(e) {
        $(this).children('#zhuce-main').css('display','block');
    },function(){
        $(this).children('#zhuce-main').css('display','none');
        $(this).children('#zhuce-main02').css('display','none');
    
    
    });
	$('.nav-out .nav-right ol li:eq(2)').hover(function(e) {
        $(this).children('#denglu-main').css('display','block');
    },function(){
        $(this).children('#denglu-main').css('display','none');
    
    
    });


})
*/




/*$(function(){
	$('.nav-out .nav-right ol #zhuce').click(function(e) {
        $('.nav-out .nav-right ol #zhuce-main').toggle();
        $('.nav-out .nav-right ol #denglu-main').css('display','none');
    });
	$('.nav-out .nav-right ol #denglu').click(function(e) {
        $('.nav-out .nav-right ol #denglu-main').toggle();
        $('.nav-out .nav-right ol #zhuce-main').css('display','none');
    });


})
*/

/*---nav--注册方式切换弹框-----*/
$(function(){
	$('#zhuce-main .tab-title01').click(function(e) {
        $(this).parent('').css('display','none').siblings().css('display','block')
    });
	$('#zhuce-main02 .tab-title02').click(function(e) {
        $(this).parent('').css('display','none').siblings().css('display','block')
    });



})

/*---header--top 搜索-----*/
$(function(){
	$('.header-right .search-btn').click(function(e) {
		if($('.header-right .search').width()==0){
			$('.header-right .search,.header-right .search input.text-xqy').animate({width:135},500);
        }else{
			$('.header-right .search,.header-right .search input.text-xqy').animate({width:0},500);
        }
    });



})
/*---nav-top 搜索-----*/
$(function(){
	$('.nav-right .search-btn').click(function(e) {
		if($('.nav-right .search').width()==0){
			$('.nav-right .search,.nav-right .search input.text-xqy').animate({width:135},500);
        }else{
			$('.nav-right .search,.nav-right .search input.text-xqy').animate({width:0},500);
        }
    });



})

/*-----header弹框效果-----*/
$(function(){
	$('.header-right .xitong').click(function(e) {
        $(this).children('.xit-sz').toggle();
        $('.header-right .name').children('.my-suiuu').css('display','none');
    });

	$('.header-right .name').click(function(e) {
        $(this).children('.my-suiuu').toggle();
        $('.header-right .xitong').children('.xit-sz').css('display','none');
    });



})

/*-----随游详情页banner轮播----*/
$(function(){
	var num=0;
	var timer=null;
	var maxnum=$('.web-banner #banner li').size()-1;

	function fn(){
		
		num++;
		if(num>maxnum){num=0}
		$('.web-banner ul').stop().animate({left:num*-830},1000)
		$('.web-banner ol').stop().animate({left:num*-195},1000)
	}
	timer=setInterval(fn,2000)
	$('.web-banner ul li,.web-banner ol li').hover(function(e) {
        clearInterval(timer)
		
    },function(){
		clearInterval(timer)
    	timer=setInterval(fn,2000)
    
    });
	
	$('.web-banner .nex').click(function(e) {
        num++;
		if(num>maxnum){num=0}
		$('.web-banner ul').stop().animate({left:num*-830},500)
		$('.web-banner ol').stop().animate({left:num*-195},500)

    });
	$('.web-banner .pre').click(function(e) {
        num--;
		if(num<0){num=maxnum}
		$('.web-banner ul').stop().animate({left:num*-830},500)
		$('.web-banner ol').stop().animate({left:num*-195},500)
    });
	$('.web-banner .prev,.web-banner .next').hover(function(e) {
        clearInterval(timer)
    },function(){
		clearInterval(timer)
		timer=setInterval(fn,2000)
	});
	
	
})



/*-----专栏qq分享-----*/
$(function(){
	$('#fenxiang').click(function(e) {
        $(this).children('#other-line').toggle()
    });

})




/*-----随友个人中心私信对话框-----*/
$(function(){
	$('.sycon .myEmail .emailCon .left ul li').click(function(e) {
        $('.sycon .myEmail .emailCon .right').toggle();
    });

})


/*-----随游-类型选择----*/
$(function(){
	$('.sylx .sylx-xiangxi .p2 span').click(function(e) {
        $(this).addClass('active').siblings().removeClass('active');
    });

})


/*-----完成页满屏显示----*/
$(function(){
	var h1=$(window).height();
	var h3=$('#footer-out').height();
	var myh=h1-h3-40-110;
	$('#finish').outerHeight(myh+'px')
	$('.forgotPaw').outerHeight(myh+'px')
	

})








