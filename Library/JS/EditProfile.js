$(document).ready(function(){
	$(document).tooltip();

	$("#edit_profile").validate({
		onkeyup: function(element) {
				var element_id = jQuery(element).attr('id');
				if(this.settings.rules[element_id].onkeyup !== false) {
				jQuery.validator.defaults.onkeyup.apply(this, arguments);
			}
		},
		rules: {
			new_password:{
				minlength:6
			},
			confirm_new_password:{
				equalTo:"#new_password"
			},
			user_name:{
				minlength:4,
				maxlength:20,
				"uniqueUserName":true,
				onkeyup:false
			}
		},
		messages: {
			user_name: {
				minlength: getErrorMessage("Username should have at least 4 characters and at most 20"),
				maxlength: getErrorMessage("Username should have at least 4 characters and at most 20")
			}, new_password: {
				minlength: getErrorMessage("password should at least have 4 characters")
			},
			confirm_new_password: {
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
		
	$("#edit_profile .edit_me").click(function(){
		$(this).toggleClass('selected');
		
		$input = $(this).next('input');
		$("label[for='" + $input.attr('id') + "']").toggleClass('selected');
		$input.prop('disabled', function(index ,value){ return toggleDisabled($(this), value); });
		
		$input.trigger('edit');
	});
	
	$('#new_password').on('edit', function(){
		$("label[for='confirm_new_password']").toggleClass('selected').toggle();
		$("#confirm_new_password").prop('disabled', function(index ,value){ return toggleDisabled($(this), value); }).trigger('edit').toggle();
	});
	
	$('#edit_profile').submit(function(){
		if($('#edit_profile').valid()) {
			$('#edit_profile .submit').hide();
		}
	});
});

function toggleDisabled($input, value) {	
	if(!value) {
		$input.removeClass('valid');
		$('#' + $input.attr('id') + '-error').remove();
	}
	
	return !value; 
}