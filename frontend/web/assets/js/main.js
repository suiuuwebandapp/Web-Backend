/**
 * Created by suiuu on 15/4/24.
 */
var Main = function() {

    // private functions & variables
    var basePath = "";

    var initMenuOpen=function(currentLi){
    };

    // public functions
    return {

        // main function
        init : function(options) {
        },
        showTip:function(tipInfo){
            alert(tipInfo);
        },
        // 打印Object 所有属性值
        printObject : function (obj){
            var temp = "";
            for(var i in obj){// 用javascript的for/in循环遍历对象的属性
                temp += i+":"+obj[i]+"\n";
            }
            alert(temp);
        }

    };


}();


/*******************************************************************************
 * Usage
 ******************************************************************************/
// Custom.init();
// Custom.doSomeStuff();
