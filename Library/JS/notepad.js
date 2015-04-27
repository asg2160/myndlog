$(function() {

	function saveNotepad(notes) {
		$('#save_notepad').prop('disabled',true);	
		$.ajax({
			url: 'Notepad',
	 	    type: 'POST',
	 	    data: "notes=" + notes + "&action=update" + "&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	$('#save_notepad').prop('disabled',false);
		    	$('#notepad .saved_text .time').text(getCurrentTime());
		    	$('#notepad .saved_text').show();
	    	}
		});
	}
	
	function getCurrentTime() {
		var date = new Date($.now());
		var mid = date.getHours() < 12 ? 'am' : 'pm';
		var timeArray = date.toTimeString().split(' ')[0];
		return timeArray + " " + mid;
	}
	
	function autoSave(time) {
		setTimeout(function(){
					$("#save_notepad").click();
					autoSave(60000);
				},time);
	}
	
	$("#save_notepad").click(function(){
		saveNotepad($("#notepad textarea").val());
	});
	
	autoSave(0);
});