$(function() {

	function saveNotepad(notes) {		
		$.ajax({
			url: 'Notepad',
	 	    type: 'POST',
	 	    data: "notes=" + notes + "&action=update" + "&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	$('#save_notepad').attr('disabled',true);
		    	window.location = window.location.href;
	    	}
		});
	}
	
	$("#save_notepad").click(function(){
		saveNotepad($("#notepad textarea").val());
	});
});