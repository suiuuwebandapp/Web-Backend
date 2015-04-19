/**
 * Custom module for you to write your own javascript functions
 */
var Main = function() {

	// private functions & variables
	var basePath = "";
	var baseLocation = basePath + "/sys/index.html";
	var iframeId = "rightFrame";
	var iframeObj = "#rightFrame";
	var modalId = "#ajaxModal";
	var modalConfirm = "#modalConfirm";
	var messageTitle = "系统提示信息";

    var initMenuOpen=function(currentLi){
        //初始化Head

        //初始化Menu
        currentLi.find("span[class='arrow']").addClass("open");
        var parentLi=$(currentLi).parent("ul").parent('li');
        parentLi.addClass("active");
        parentLi.addClass("open");

        if(!currentLi.hasClass('menu_top')&&!parentLi.hasClass('menu_top')){
            initMenuOpen(parentLi);
        }
    };

    /**
     * 1.初始化左侧菜单点击事件
     * 2.初始化页面默认加载内容和部分样式问题
     * 3.初始化head菜单
     */
    var initHeadInfo=function(currentLi){
        var titleText=currentLi.text()!=undefined?currentLi.text():'';
        var titleIntro=currentLi.find("a").attr("intro")!=undefined?currentLi.find("a").attr("intro"):"";
        $("#headTitle").html(titleText+' &nbsp;&nbsp;<small>'+titleIntro+'</small>');
        $("#headUl").html('');
        buildHeadInfo(currentLi);
        $("#headUl a").click(function() {
            var href = ($(this).attr('href')).replace('#~', '');
            $.get(href, function (data) {
                $("#div_main_container").html(data);
            });
        });

    };

    /**
     * 递归构建Head 面包屑
     * @param currentLi
     */
    var buildHeadInfo=function(currentLi){
        var text=currentLi.children("a").text();
        var url=currentLi.children("a").attr("href");

        $("#headUl").html('<li><i class="fa fa-angle-right"></i><a href="'+url+'">'+text+'</a></li>'+$("#headUl").html());
        var parentLi=$(currentLi).parent("ul").parent('li');
        if(!currentLi.hasClass('menu_top')&&!parentLi.hasClass('menu_top')){
            buildHeadInfo(parentLi);
        }else{
            var defaultLi=$("#left_menu_ul li[class='default_menu']");
            url=$(defaultLi).find("a").attr("href");
            $("#headUl").html('<li><i class="fa fa-home"></i><a href="'+url+'"> 首页</a></li>'+$("#headUl").html());
        }
    }

    /**
     * 根据URL初始化左侧菜单显示情况
     * @param $url
     */
    var initLeftMenuByUrl=function (url){
        //判断地址栏是否有相应的地址
        if(url.indexOf('#~')>-1){
            //如果有，加载相应的地址
            var href=url.substring(url.indexOf('#~')+2,url.length);
            $.get(href,function(data){
                $("#div_main_container").html(data);
            });
            var aObj="#left_menu_ul li a[href='#~"+href+"']";

            $("#left_menu_ul li").removeClass("active");
            initHeadInfo($(aObj).parent("li").eq(0));
            initMenuOpen($(aObj).parent("li").eq(0));
        }else{
            var defaultLi=$("#left_menu_ul li[class='default_menu']");
            var defaultUrl=$(defaultLi).find("a").attr("href");

            var href=defaultUrl.replace("#~","");
            $.get(href,function(data){
                $("#div_main_container").html(data);
            });
            $("#left_menu_ul li").removeClass("active");
            initHeadInfo(defaultLi);
            initMenuOpen(defaultLi);
        }
    };

    /**
     * 初始化左侧菜单
     */
    var initLeftMenu=function(){
        //1.初始化左侧菜单点击事件
        $("#left_menu_ul a").click(function(){
            if(($(this).attr('href')).indexOf('#~')>-1){
                var href=($(this).attr('href')).replace('#~','');
                $.get(href,function(data){
                    $("#div_main_container").html(data);
                });
                $("#left_menu_ul li").removeClass("active");
                //$("#left_menu_ul li[class='open']").addClass("active");
                $(this).parents(".menu_top").addClass("active");

                initHeadInfo($(this).parent("li").eq(0));
            }
        });

        //2.初始化页面默认加载内容和部分样式问题
        var url=window.location.href;
        initLeftMenuByUrl(url);
    };

	// public functions
	return {

		// main function
		init : function(options) {
			// initialize here something.
			this.basePath = options.basePath;
			//window.setInterval("Main.reinitIframe()", 200);
            initLeftMenu();
		},
        refresh:function(url){
            var l=window.location.href;
            if(url!=""){
                if(l.indexOf('#~')>-1){
                    l=l.substring(0,l.indexOf('#~'));
                }
                l=l+"#~"+url;
            }
            window.location.href=l;
        },
        refreshContent:function(url){
            if(url==null||url==''){
                url=window.location.href;
            }
            initLeftMenuByUrl(url);
        },
        refreshContentAjax:function(action){
            $.get(action, function (data) {
                $("#div_main_container").html(data);
            });
        },
        goAction:function(action){
            var url=window.location.href;
            if(url.indexOf('#~')>-1){
                url=url.substring(0,url.indexOf('#~'))
            }
            url=url+"#~"+action;

            this.refreshContent(url);

        },
		// Open Moadal Method
		openModal : function(src) {
			var modalDiv = $(modalId).attr("data-target");
			$(modalDiv).html('<img src="' + basePath + '/assets/global/img/ajax-modal-loading.gif" alt="" class="loading">');
			$(modalId).attr("href", src);
			$(modalId).click();
			$(modalDiv).load(src);
		},
		// Iframe 自适应高度
		reinitIframe : function() {
			var iframe = document.getElementById(iframeId);
			try {
				var iframeHeight = Math.max(
                   iframe.contentWindow.window.document.documentElement.scrollHeight,
                   iframe.contentWindow.window.document.body.scrollHeight
                );
				iframe.height = iframeHeight;
			} catch (ex) {
			}
		},
		successTip : function(message) {
			$.gritter.add( {
				title : messageTitle,
				text : message,
				image : basePath + '/assets/global/img/envelope.png',
				sticky : false,
				class_name : "successTip"
			});
		},
		infoTip : function(message) {
			$.gritter.add( {
				title : messageTitle,
				text : message,
				image : basePath + '/assets/global/img/envelope.png',
				sticky : false,
				class_name : "infoTip"
			});
		},
		errorTip : function(message) {
			$.gritter.add( {
				title : messageTitle,
				text : message,
				image : basePath + '/assets/global/img/envelope.png',
				sticky : false,
				class_name : "errorTip"
			});
		},
		confirmTip : function(message, callBack) {

			var html = '<div id="modalConfirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true" style="display: none;z-index:10053">';
			html += '  	<div class="modal-dialog">';
			html += '    	<div class="modal-content">';
			html += '        	<div class="modal-header">';
			html += '            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>';
			html += '				<h4 class="modal-title">' + messageTitle + '</h4>';
			html += '			</div>';
			html += '			<div class="modal-body">';
			html += '				<p>#Content#</p>';
			html += '			</div>';
			html += '			<div class="modal-footer">';
			html += '				<button id="comfirm_yes" class="btn blue">确认</button>';
			html += '				<button id="comfirm_no"  class="btn default">取消</button>';
			html += '			</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';

			if ($(modalConfirm).html() != undefined) {
				$(modalConfirm).remove()
			}
			html = html.replace("#Content#", message);
			$('body').append(html);

			$(modalConfirm).modal('show');
			// Modal Center
			var modalDialog = $(modalConfirm).children();
			var height = (($(window).height() / 2) - 30 - 150);
			$(modalDialog).css("margin-top", height);

			$("#comfirm_yes").bind("click", function() {
				$(modalConfirm).modal('hide');
				callBack();
			});
			$("#comfirm_no").bind("click", function() {
				$(modalConfirm).modal('hide');
			});
		},
		// 显示等待处理状态框
		showWait : function(el, centerY) {
			var element = el != undefined ? el : jQuery(iframeObj).contents().find("body");
			element = jQuery(element);
			// 如果在main页面找不到对应元素，则去Ifame中查找
			if (element.height() == null) {
				element = jQuery(iframeObj).contents().find(el);
			}
			if (element.height() <= 400) {
				centerY = true;
			}
			element.block( {
				message : '<img src=' + basePath + '"/assets/global/img/ajax-loading.gif" align="正在提交，请稍后。。。">',
				centerY : centerY != undefined ? centerY : true,
				css : {
					top : '10%',
					border : 'none',
					padding : '2px',
					backgroundColor : 'none'
				},
				overlayCSS : {
					backgroundColor : '#333333',
					opacity : 0.05,
					cursor : 'wait'
				}
			});
		},
		hideWait : function(el, clean) {
			var element = el != undefined ? el : jQuery(iframeObj).contents().find("body");
			element = jQuery(element);
			// 如果在main页面找不到对应元素，则去Ifame中查找
			if (element.height() == null) {
				element = jQuery(iframeObj).contents().find(el);
			}
			jQuery(element).unblock( {
				onUnblock : function() {
					jQuery(el).css('position', '');
					jQuery(el).css('zoom', '');
				}
			});
		},
		// 刷新Iframe 表格
		refrenshIframeTable : function() {
			document.getElementById(iframeId).contentWindow.TableAjax.refresh();
		},
		callIframeMethod : function( methodName) {
			methodName="document.getElementById(iframeId).contentWindow."+methodName;
			eval(methodName)();
		},
		// 改变页面地址
		changeLocationUrl : function(urlId, params) {
			var href = baseLocation + "?urlId=" + urlId;
			if (params != "" && params!= undefined) {
				href += "&" + params;
			}
			window.location.href = href;
		},
		// 全选CheckBox
		selectAll : function(id, current) {
			var checks = $(id + " :checkbox");// 获取需要全选的jquery对象
			var flag = current.checked;
			checks.each(function(i) {
				if (i == 0) {
					return true;
				}
				if (flag) {
					$(checks[i]).attr("checked", true);
					$(checks[i]).parent("span").attr("class", "checked");
				} else {
					$(checks[i]).attr("checked", false);
					$(checks[i]).parent("span").attr("class", "");
				}
			});
		},
		// 打印Object 所有属性值
		printObject : function (obj){ 
			var temp = ""; 
			for(var i in obj){// 用javascript的for/in循环遍历对象的属性
				temp += i+":"+obj[i]+"\n"; 
			} 
			alert(temp);
		} ,
		//加载图片预览
		getInitialPreviewImage: function (str){
			var result=null;
			if(str!=""){
				var arr=str.split(",");
				var info,url,config;
				var previewArr=new Array();
				var configArr=new Array();
				for(var i=0;i<arr.length;i++){
					if(arr[i]==''){ continue; }
					info=arr[i].split("|");
					url='<img src="'+info[0]+'" class="'+info[2]+'" >';
					config={'caption':info[1],'width':'80px','url':info[3]};
					previewArr.push(url);
					configArr.push(config);
				}
				result=new Array(previewArr,configArr);
			}
			return result;
		},
		//加载声音预览
		getInitialPreviewSound: function (str){
			var result=null;
			if(str!=""){
				var arr=str.split(",");
				var info,url,config;
				var previewArr=new Array();
				var configArr=new Array();
				for(var i=0;i<arr.length;i++){
					if(arr[i]==''){ continue; }
					info=arr[i].split("|");
					url='<audio controls><source src="'+info[0]+'" class="'+info[2]+'" >Test </audio>';
					config={'caption':info[1],'width':'110px','url':info[3]};
					previewArr.push(url);
					configArr.push(config);
				}
				result=new Array(previewArr,configArr);
			}
			return result;
		},
		//初始化预览
		initPreview :function (array,str,type){
			array=null;
			if(type=='image'){
				array=this.getInitialPreviewImage(str);
			}else if(type=='sound'){
				array=this.getInitialPreviewSound(str);
			}
			if(array==null){
				array=new Array(new Array(),new Array());
			}
			return array;
		}
		
	};


}();

// MODULE URL
var ModuleUrl = {
	addRoleUrl  :  '8f4193c3dc554efb9c2f9d83656d7ae9',
	roleListUrl :  '5e433f8fbc624f9db6f2f69c93683fd3',
	editRoleUrl :  '8b2dc88318ac4c5c9c617f6f23665f76',
	
	addMovieUrl            : '27f141acfae84989be0786ccb7dbd3ce',
	movieListUrl           : '7c1050abe67c4a809a769a4128287822',
	editMovieUrl           : 'cb01d37d34074f4d88380db8abd4716f',
	editMovieDetailUrl     : '2444d87011b442d0b21b99b1214c176d',
	editMovieDownloadUrlId : '5fa9973031b548dfbb68318c91688020',
	editMovieRoleUrlId     : '692cf956b9f84fac81655b6f28115776'
	
}

/*******************************************************************************
 * Usage
 ******************************************************************************/
// Custom.init();
// Custom.doSomeStuff();
