var FormValidation = function() {

	var form = $('#form_validate');
	
	var handleValidation = function(methodName) {
		// for more info visit the official plugin documentation:
		// http://docs.jquery.com/Plugins/Validation
		var callMethod = methodName;
		var error = $('.alert-danger', form);
		var success = $('.alert-success', form);
		var isSubmit = function(){
			var result=true;
			if($(form).attr("isSubmit")!=true){
				result=false;
			}
			return result
		};
		var submitValidateMethod = function(){
			var method=$(form).attr("submitValidateMethod");
			var result=true;
			if(method!=undefined){
				result=eval(method)();
			}
			return result;
		};
		error.children("span").html("您的提交验证不通过，请检查修改后重试！");
		success.children("span").html("您的验证已经通过！");
		form.validate( {
			errorElement : 'span', // default input error message container
			errorClass : 'help-block', // default input error message class
			focusInvalid : true, // do not focus the last invalid input
			focusCleanup : true,
			ignore : 'ignore',

			invalidHandler : function(event, validator) { // display error // alert on form // submit
				//success.hide();
				//error.show();
				

			 	var errors=validator.errorList;
			 	if(errors.length>0){
			 		var val_error=errors[0];
			 		$(val_error.element).focus();
			 	}
			 	
			 	submitValidateMethod();

			},

			errorPlacement : function(error, element) { // render error
														// placement for each
														// input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");
				icon.attr("data-placement", "left");
				icon.attr("data-animation", "false");
				icon.attr("data-original-title", error.text()).tooltip();
			},
			
			highlight : function(element) { // hightlight error inputs
				$(element).closest('.valdate').addClass('has-error'); // set error class to the control group
				$(element).closest('.valdate').removeClass('has-success');
			},

			unhighlight : function(element) { // revert the change done by
				// hightlight
			},

			success : function(label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.valdate').removeClass('has-error').addClass('has-success'); // set success class to the
				// control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},
			
			submitHandler : function(form) {
				//success.show();
				//error.hide();f
				if(submitValidateMethod()){
					var result=true;
					//BeforSubmit Validate
					if(callMethod!=""){
						 result=eval(callMethod)();
					}
					//Whould you need form submit?
					if(isSubmit&&result){
						form.submit();
					}
				}
			}
		});

	};
	var setErrorText = function(element,errorText){
		
		var icon = $(element).parent('.input-icon').children('i');
		icon.removeClass('fa-check').addClass("fa-warning");
		icon.attr("data-placement", "left");
		icon.attr("data-animation", "false");
		icon.attr("data-original-title",errorText).tooltip();
		
		$(element).closest('.valdate').removeClass('has-success');
		$(element).closest('.valdate').addClass('has-error');
	};
	
	var setSuccessfully = function(element){
		var icon = $(element).parent('.input-icon').children('i');
		icon.attr("data-original-title","");
		$(element).closest('.valdate').removeClass('has-error').addClass('has-success'); // set success class to the
		// control group
		
		icon.removeClass("fa-warning").addClass("fa-check");
	};

	return {
		// main function to initiate the module
		init : function(methodName) {
			handleValidation(methodName);
		},
		resetForm : function() {
			$(form).find("input").each(function(){
				$(this).closest('.valdate').removeClass('has-success');
				$(this).val("");
				var icon = $(this).parent('.input-icon').children('i');
				icon.removeClass("fa-check");
			});
		},
		setError:function(element,text){
			setErrorText(element,text);
		},
		setSuccess:function(element){
			setSuccessfully(element);
		}

	};

}();