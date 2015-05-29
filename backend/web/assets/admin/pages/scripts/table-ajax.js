var TableAjax = function () {

	//tabledata
	var tableInfoDefault = {
		'tableOrder' : [0,'asc'],
		'tableData'  : {
			'showAll':false
		}
	}
	var tableInfo={};
	var tableDatas=null;
	
	var fixHelper = function(e, ui) {  
		//console.log(ui)   
		ui.children().each(function() {  
			$(this).width($(this).width());     //在拖动时，拖动行的cell（单元格）宽度会发生改变。在这里做了处理就没问题了   
		});  
		return ui;  
	};  
	
    return {

        //main function to initiate the module
        init: function (options) {
            if (!jQuery().dataTable) {
                return;
            }
            this.tableInfo = $.extend({}, this.tableInfoDefault, options);
            var tableParamInfo={
	            "dom" : "<'row'<'col-md-6 col-sm-12'><'col-md-12 col-sm-12'f>r>t<'row'<'col-md-3 col-sm-12'l><'col-md-4 col-sm-12'i><'col-md-5 col-sm-12'p>>", //default layout without horizontal scroll(remove this setting to enable horizontal scroll for the table)
		        "processing": true,
		        "serverSide": true,
		        "stateSave" : false,
		        "pagingType": "bootstrap",
		        "bAutoWidth":false,
		        "bFilter"   :false,
		        "ajax": {
		            "url": this.tableInfo.tableUrl,
		            "type": "POST",
		            "data": this.tableInfo.tableData
		        },
		        "order" : [],
		        "columnDefs": this.tableInfo.tableColumn,
				"language": {
					"processing": '<i class="fa fa-coffee"></i>&nbsp;正在加载数据...',
					"lengthMenu":   "_MENU_",
					"zeroRecords":  "<div class='text-center text-danger'>没有匹配结果<div>",
					"info":         '<div class="dataTables_paginate paging_bootstrap"><ul class="pagination" style="visibility: visible;"><li class="next disabled"><a href="javascript:;">'+'显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项'+'</a></li></ul></div>',
					"infoEmpty":    '<div class="dataTables_paginate paging_bootstrap"><ul class="pagination" style="visibility: visible;"><li class="next disabled"><a href="javascript:;">显示第 0  至 0 项结果，共 0 项 </a></li></ul></div>',
					"infoFiltered": "",
					"infoPostFix":  "",
					"search":       "搜索：",
					"url":          "",
					"oPaginate": {
						"first":    "首页",
						"previous": "上页",
						"next":     "下页",
						"last":     "末页"
		        	}
				},
				"oAria": {
					"sSortAscending":  ": 以升序排列此列",
					"sSortDescending": ": 以降序排列此列"
				},
				"fnDrawCallback":this.tableInfo.fnDrawCallBack
		    };
            if(this.tableInfo.tableOrder!=""){
            	tableParamInfo.order = [this.tableInfo.tableOrder];
            }
            this.tableDatas=$(this.tableInfo.tableObj).dataTable(tableParamInfo);
			jQuery(this.tableInfo.tableDiv).find('input').addClass("form-control input-medium"); // modify table search input
			jQuery(this.tableInfo.tableDiv).find('select').addClass("form-control input-small"); // modify table per page dropdown
			
			//Enter Serach
			document.onkeydown = function(e){
			    var ev = document.all ? window.event : e;
			    if(ev.keyCode==13) {
			    	TableAjax.refresh();
			    }
			};
			if(this.tableInfo.sortable){
				jQuery(this.tableInfo.tableObj).find("tbody").sortable({                //这里是talbe tbody，绑定 了sortable   
					helper: fixHelper,                  //调用fixHelper   
					axis:"y"
				}).disableSelection(); 
			}
			return this;
			
        },
        refresh : function(){
        	var url=this.tableInfo.tableUrl;
        	var params=$(this.tableInfo.formObj).serialize();
        	if(this.tableInfo.tableUrl.indexOf("?")==-1){
        		url=url+"?";
        	}
        	if(params!=""){
        		url=url + "&" +params;
    		}
        	if(this.tableInfo.tableData.showAll==true){
        		url=url + "&showAll=true";
        	}
			this.tableDatas.fnReloadAjax(url);
        },
        refreshCurrent:function(){
            var url=this.tableInfo.tableUrl;
            var params=$(this.tableInfo.formObj).serialize();
            if(this.tableInfo.tableUrl.indexOf("?")==-1){
                url=url+"?";
            }
            if(params!=""){
                url=url + "&" +params;
            }
            if(this.tableInfo.tableData.showAll==true){
                url=url + "&showAll=true";
            }
            //模拟刷新当前页（点击当前页按钮）
            //$(this.tableInfo.tableObj+"_paginate").find("li[class='active']").click();
            var currentPage=TableAjax.getCurrentPage();
            if(currentPage!=1){
                url=url + "&currentPage="+currentPage;
            }
            this.tableDatas.fnReloadAjax(url,null,true);
        },
        deleteRefresh:function(){
        	var size=$(this.tableInfo.tableObj).find("tbody").find("tr").size();
        	if(size==1){
        		$(this.tableInfo.tableObj+"_paginate").find("li[class='active']").prev().click();
        	}else{
        		$(this.tableInfo.tableObj+"_paginate").find("li[class='active']").click();
        	}
        },
        getCurrentPage : function(){
        	var currentPage=$(this.tableInfo.tableObj+"_paginate").find("li[class='active']").find("a").html();
        	return currentPage;
        }

    };

}();


jQuery.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
	// DataTables 1.10 compatibility - if 1.10 then `versionCheck` exists.
	// 1.10's API has ajax reloading built in, so we use those abilities
	if ( jQuery.fn.dataTable.versionCheck ) {
		var api = new jQuery.fn.dataTable.Api( oSettings );
		if ( sNewSource ) {
			api.ajax.url( sNewSource ).load( fnCallback, !bStandingRedraw );
		}
		else {
			api.ajax.reload( fnCallback, !bStandingRedraw );
		}
		return;
	}
	if ( sNewSource !== undefined && sNewSource !== null ) {
		oSettings.sAjaxSource = sNewSource;
	}
	// Server-side processing should just call fnDraw
	if ( oSettings.oFeatures.bServerSide ) {
		this.fnDraw();
		return;
	}

	this.oApi._fnProcessingDisplay( oSettings, true );
	var that = this;
	var iStart = oSettings._iDisplayStart;
	var aData = [];

	this.oApi._fnServerParams( oSettings, aData );
	oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
		/* Clear the old information from the table */
		that.oApi._fnClearTable( oSettings );

		/* Got the data - add it to the table */
		var aData =  (oSettings.sAjaxDataProp !== "") ?
			that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

		for ( var i=0 ; i<aData.length ; i++ )
		{
			that.oApi._fnAddData( oSettings, aData[i] );
		}

		oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

		that.fnDraw();

		if ( bStandingRedraw === true )
		{
			oSettings._iDisplayStart = iStart;
			that.oApi._fnCalculateEnd( oSettings );
			that.fnDraw( false );
		}

		that.oApi._fnProcessingDisplay( oSettings, false );

		/* Callback user function - for event handlers etc */
		if ( typeof fnCallback == 'function' && fnCallback !== null )
		{
			fnCallback( oSettings );
		}
	}, oSettings );
};
