$(document).ready(function(){

	$(document).tooltip();

	/* SIGN IN */
	$('#copy .thumbs img').click(function(){
		var source = $(this).attr('src');
		var heading = $(this).attr('alt');
		$('#copy .preview img').attr('src',source);
		$('#copy .preview .text').html(heading);
		$('#copy .thumbs .selected').removeClass('selected');
		$(this).addClass('selected');
		
		if($(this).parent().prev('li').length)
			$('#copy .preview .left_arrow').show();
		else
			$('#copy .preview .left_arrow').hide();
			
		if($(this).parent().next('li').length)
			$('#copy .preview .right_arrow').show();
		else
			$('#copy .preview .right_arrow').hide();
	});
	
	 $('#copy .preview .left_arrow').click(function(){
	 	$('#copy .thumbs img.selected').parent().prev('li').children('img').click();
	 });
	 
	 $('#copy .preview .right_arrow').click(function(){
		$('#copy .thumbs img.selected').parent().next('li').children('img').click();
	 });
	  
	$("#sign_in").validate({
		rules: {
			email:{
				required:true,
				email:true
			},password:{
				required:true,
				minlength:6
			}
		},
		messages: {
			email: getErrorMessage("please enter a valid email address"),
			password: {
				required: getErrorMessage("please enter a password"),
				minlength: getErrorMessage("password should at least have 6 characters")
			}
		},errorPlacement: function(error, element) {
			error.insertAfter(element);
		},success: function(label) {
			$("<span class='success_symbol'></span>").appendTo(label);
		}
	});
	
	$('#sign_in').submit(function(){
		if($('#sign_in').valid()) {
			$('#sign_in .submit').hide();
		
			if($("#sign_in input[name=remember_me]").is(':checked')) {
				$.cookie("rememberMeOnMyndLogEmail", $('#sign_in input[name=email]').val());
				$.cookie("rememberMeOnMyndLogPwd", $('#sign_in input[name=password]').val());
			} else {
				$.removeCookie("rememberMeOnMyndLogEmail");
				$.removeCookie("rememberMeOnMyndLogPwd");
			}
		}
	});
	
	$('#show_sign_in').click(function(){
		$('#sign_in_wrapper').show();
		$('#register_wrapper span').click();
		$(this).addClass('disabled').attr('disabled','disabled');
	});
	
	$('#sign_in_box .links a').click(function(){
		$('#sign_in_box form').toggle();
		$('#sign_in_box .links a').toggle();
	});
	
	/* REGISTER */
	
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
				"illegalUserName":true,
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
			$("<span class='success_symbol'></span>").appendTo(label);
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
	
	$.validator.addMethod("illegalUserName", function(value) {
		return isUserNameIllegal(value);
	}, getErrorMessage('That user-name is not allowed.'));
	
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
	
	$('.thumbs img:first').click();
});
