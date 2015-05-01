$(function(){

	var tabs = function(e1, e2, e3){
	  var e1 = $('a', e1);
	  var e2 = $(e2);
	  e1.click(function(){
	  	$('.error').remove();/*在本页面中没用--移除错误类---*/
	    if(!$(this).hasClass('active')){
	      e1.removeClass('active');
	      $(this).addClass('active');
	      var idx = e1.index(this);
	      e2.fadeOut(0);
	      $(e2[idx]).fadeIn(0);
	      $(e3).attr('href',$(this).attr('href'));
	    }
	  });
	  e1.click(function(){
	    return false;
	  })
	}
		tabs('.con-nav', '.TabCon');
		tabs('.innerNav', '.innerCon');
		tabs('.tabTitle', '.tabCon');
		tabs('.myTit', '.myCon');
		tabs('.recTit', '.slideRec');
		tabs('.aboutNav', '.aboutCon')
		/*通用----*/
		/*	tabs('.logintab', '.logincon');*/
})


