// JavaScript Document


/*-----------随-游-订单---------------*/

$(function(){
	$('.sy_order .box .pay').click(function(e) {
        $('.order_pay').animate({height:'6.5rem'},500);
    });
	$('.order_pay .btn').click(function(e) {
        $('.order_pay').animate({height:'0'},500);
    });

})

/*-----------浮层--------------*/
$(function(){
	var h=$(window).height();
	var w=$(window).width();
	$('.mask').height(h).width(w);
})






















