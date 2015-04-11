$(document).ready(function(){
	$(document).tooltip();
	
	$("#register").validate({
		onkeyup: function(element) {
				var element_id = jQuery(element).attr('id');
				if(this.settings.rules[element_id].onkeyup !== false) {
				jQuery.validator.defaults.onkeyup.apply(this, arguments);
			}
		},
		rules: {
			email:{
				required:true,
				email:true,
				"uniqueEmail":true,
				onkeyup: false
			},
			password:{
				required:true,
				minlength:6
			},
			confirm_password:{
				required:true,
				equalTo:"#password"
			},
			user_name:{
				required:true,
				minlength:4,
				maxlength:20,
				"uniqueUserName":true,
				onkeyup: false
			}
		},
		messages: {
			email: {
				required: getErrorMessage("please enter a valid email address"),
				email: getErrorMessage("please enter a valid email address")
			}, user_name: {
				required: getErrorMessage("please enter a user name"),
				minlength: getErrorMessage("Username should at least have 4 characters"),
				maxlength: getErrorMessage("Username should have at most 20 characters")
			},
			password: {
				required: getErrorMessage("please enter a password"),
				minlength: getErrorMessage("password should at least have 6 characters")
			},
			confirm_password: {
				required: getErrorMessage("please confirm your password"),
				equalTo: getErrorMessage("passwords do not match")
			}
		},errorPlacement: function(error, element) {
			error.insertAfter(element);
		},success: function(label) {
			$("<span class='success_symbol'>&#10004;</span>").appendTo(label);
		}
	});
	
	$.validator.addMethod("uniqueUserName", function(value) {
		
		var isUnique = false;
		$.ajax({
				dataType: 'json',
				url: getURL('User'),
		 	    type: 'POST',
		 	    data: "user_name=" + value + "&action=check_unique&isAjax=1",
		   	    async: false,
		  	    cache: false,
		 	    timeout: 30000,
			    success: function(response) { 
					isUnique = response.isUnique;
		    	}
			});
		return isUnique;
		
	}, getErrorMessage('This user-name has already been taken '));
	
	$.validator.addMethod("uniqueEmail", function(value) {
		
		var isUnique = false;
		$.ajax({
				dataType: 'json',
				url: getURL('User'),
		 	    type: 'POST',
		 	    data: "email=" + value + "&action=check_unique&isAjax=1",
		   	    async: false,
		  	    cache: false,
		 	    timeout: 30000,
			    success: function(response) { 
					isUnique = response.isUnique;
		    	}
			});
		return isUnique;
		
	}, getErrorMessage('This email has already been taken'));
	
	$('#register').submit(function(){
		if($('#register').valid()) {
			$('#register .submit').hide();
		}
	});
	
	$("#register_wrapper span").click(function(){
   		$("#register_wrapper").hide();
   		$('#show_register').removeClass('disabled').removeAttr('disabled');
	});
	
	$('#show_register').click(function(){
		$('#register_wrapper').show();
		$('#sign_in_wrapper span').click();
		$(this).addClass('disabled').attr('disabled','disabled');
	});
	
	$('#mashup_page .sign_up').click(function(){
		$('#show_register').click();
	});
});