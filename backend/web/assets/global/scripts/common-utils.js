/**
Custom module for you to write your own javascript functions
**/
var CommonUtils = function () {

    // private functions & variables

	
    // public functions
    return {

        //main function
        init: function (options) {
        },
		//全选CheckBox
		selectAll:function(id,current){
			var checks=$(id+" :checkbox");//获取需要全选的jquery对象
			var flag=current.checked;
			checks.each(function(i){
				if(i==0){return true;}
				if(flag){
					$(checks[i]).prop("checked",true);
					$(checks[i]).parent("span").attr("class","checked");
				}else{
					$(checks[i]).prop("checked",false);
					$(checks[i]).parent("span").attr("class","");
				}
			});
		},
		//获取所有模块的选中项
		getAllSelect:function (id){
			var checks=$(id+" :checkbox");
			var val="";
			checks.each(function(i){
				if(i==0){return true;}
				if(this.checked==true){
					val=val+this.value+",";
				}
			});
			return val;
		}
    };

}();

/***
Usage
***/
//Custom.init();
//Custom.doSomeStuff();