function getStyle(obj,name){
	return (obj.currentStyle || getComputedStyle(obj,false))[name];
}
function move(obj,json,options){
	//默认值
	options=options || {};
	options.time=options.time || 700;
	options.type=options.type || 'ease-out';
	
	clearInterval(obj.timer);
	
	var count=Math.round(options.time/30);
	
	var start={};
	var dis={};
	for(var name in json){
		start[name]=parseFloat(getStyle(obj,name));
		//处理样式默认没有给值
		if(isNaN(start[name])){
			switch(name){
				case 'width':
					start[name]=obj.offsetWidth;
					break;
				case 'height':
					start[name]=obj.offsetHeight;
					break;
				case 'left':
					start[name]=obj.offsetLeft;
					break;
				case 'top':	
					start[name]=obj.offsetTop;
					break;	
			}
		}
		dis[name]=json[name]-start[name]
	}
	
	var n=0;
	obj.timer=setInterval(function(){
		n++;
		
		for(var name in json){
			switch(options.type){
				case 'linear':
					var a=n/count;
					var cur=start[name]+dis[name]*a;
					break;
				case 'ease-in':
					var a=n/count;
					var cur=start[name]+dis[name]*Math.pow(a,3);
					break;
				case 'ease-out':
					var a=1-n/count;
					var cur=start[name]+dis[name]*(1-Math.pow(a,3));
					break;
			}
			
			if(name=='opacity'){
				obj.style.opacity=cur;
				obj.style.filter='alpha(opacity:'+cur*100+')';
			}else{
				obj.style[name]=cur+'px';
			}
		}
		
		if(n==count){
			clearInterval(obj.timer);	
			options.fn && options.fn();
		}
	},30);
}