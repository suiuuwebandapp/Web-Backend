// JavaScript Document
// JavaScript Document
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

/*addMouseWheel(obj,fn)*/
function addMouseWheel(obj,fn)
{
	if(window.navigator.userAgent.toLowerCase().indexOf('firefox')!=-1){
		obj.addEventListener('DOMMouseScroll',fnWheel,false);
	}else{
		obj.onmousewheel=fnWheel;
	}
	//确定滚动方向
	function fnWheel(ev){
		var down=true;
		var oEvent = ev||event;
		if(oEvent.wheelDelta){
			down=oEvent.wheelDelta<0;
		}else{
			down=oEvent.detail>0;
		}
		//down=======上下  上false 下true
		fn&&fn(down);
		
		oEvent.preventDefault&&oEvent.preventDefault();
		return false;
	}
}

window.onload=function(){
/*登录注册*/
/*	var zhuce=document.getElementById('zhuce');
	var denglu=document.getElementById('denglu');
	var zcmain=document.getElementById('zhuce-main');
	var dlmain=document.getElementById('denglu-main');
	var liji=document.getElementById('liji');
	var ljzc=document.getElementById('ljzc');
	
		zhuce.onclick=function(){
			if(zcmain.style.display=="block"){
			   zcmain.style.display="none";	
			}else{
				zcmain.style.display="block";					
			}
		};
		denglu.onclick=function(){
			if(dlmain.style.display=="block"){
			   dlmain.style.display="none";	
			}else{
				dlmain.style.display="block";					
			}
		};
		liji.onclick=function(){
			dlmain.style.display="none";		
		};
		ljzc.onclick=function(){
			zcmain.style.display="none";		
		};
*/	
/*登录注册*/
	var oMdd=document.getElementById('mdd-btn');
	var oBtn=oMdd.getElementsByTagName('li');
	var oBox=document.getElementById('mdd-right-box');
	var aDiv=getByClass(oBox,'show');
	var oBtnprev=document.getElementById('mdd-prev');
	var oBtnnext=document.getElementById('mdd-next');
	
	var now=0;
	var left=0;
	oBox.style.width=aDiv[0].offsetWidth*aDiv.length+"px";
	for(var i=0;i<oBtn.length;i++){
		(function(index){
			oBtn[i].onclick=function(){
				now=index;
				tab();
			}
		})(i);
	}
	/*tab*/
	function tab(){
		for(var i=0;i<oBtn.length;i++){
			oBtn[i].className="";	
		}	
		oBtn[now].className="mdd-active";
		move(oBox,{left:-aDiv[0].offsetWidth*now},{time:1000});		
	}
	/*上一个*/
	oBtnprev.onclick=function(){
		if(now>0){
			now--;	
		}else{
			now=oBtn.length-1;
		}
		tab();
	};
	/*下一个*/
	oBtnnext.onclick=function(){
		now++;
		if(now==oBtn.length){
			now=0;
		}
		tab();
	};	
	
	addMouseWheel(oBox,function(down){
			if(down){
				now++;
			if(now==oBtn.length){
				now=0;
			}
			tab();
		}else{
		if(now>0){
				now--;	
			}else{
				now=oBtn.length-1;
			}
			tab();
		}
	});


}