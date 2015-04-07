$(document).ready(function(){

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
			$("<span class='success_symbol'>&#10004;</span>").appendTo(label);
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
	
	$("#sign_in_wrapper span").click(function(){
   		$("#sign_in_wrapper").hide();
   	   	$('#show_sign_in').removeClass('disabled').removeAttr('disabled');
	});
	
	$('#show_sign_in').click(function(){
		$('#sign_in_wrapper').show();
		$('#register_wrapper span').click();
		$(this).addClass('disabled').attr('disabled','disabled');
	});
	
	$('#mashup_page .login').click(function(){
		$('#show_sign_in').click();
	});
});
