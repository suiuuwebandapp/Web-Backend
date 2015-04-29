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
function xcbjy(){
	var oTab=document.getElementById('bjy');
	var oDiv=oTab.getElementsByTagName('div')[0];
	var oLi=oDiv.getElementsByTagName('li');

	for(var i=0;i<oLi.length;i++){
		oLi[i].onclick=function(){
			if(this.className == "active-bj"){
				this.className = ""
			} else {
				this.className = "active-bj";
			}
	
		};	
	}	
};
function bz(id,sClass){
	var oBox=document.getElementById('bjy-box');
	var oDiv=getByClass(oBox,sClass);
	var oBtn=document.getElementById('bz').getElementsByTagName('li');
	var oBjyprev=document.getElementById('bjy-prev');
	var oBjynext=document.getElementById('bjy-next');
	 var iNow = 0;
	 for(var i = 0; i < oBtn.length; i++){
		 oBtn[i].index = i; 
		 oBtn[i].onclick = function(){
			 iNow = this.index;
             if(iNow==0){
                 $(oBjyprev).hide();
             }else{
                 $(oBjyprev).show();
             }
			 tab();
				 
		 };
	 };
	 	 
	 function tab(){
		 for(var i = 0; i < oBtn.length; i++){
			  oBtn[i].className = "";
			  oDiv[i].style.display = "none";
		 }
		 oBtn[iNow].className = "active";
		 oDiv[iNow].style.display = "block";	 
	 }
	 
	 
	 oBjyprev.onclick = function(){
	 	iNow--;
		if(iNow == -1){
			iNow = oBtn.length - 1;
		}
         if(iNow==0){
           $(oBjyprev).hide();
         }else{
             $(oBjyprev).show();
         }
		tab();
	 };
	 
	 oBjynext.onclick = next;
	 
	 function next(){
	 	iNow++;
		if(iNow == oBtn.length){
			iNow = 0;
		}
         if(iNow==0){
             $(oBjyprev).hide();
         }else{
             $(oBjyprev).show();
         }
		tab();
	 }
};
