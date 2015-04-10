$(function() {
	
	window.scrollTo(0,0);
	window.homeLazyLoad = true;
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height()-100) {
			loadOlder();
		}
	});

	loadSummaryViewAndBindEvents();
	
	function loadSummaryViewAndBindEvents() {

		createEvents();
		slimscrollForThoughts();			
		
		// keep write box open if cookie is set
		if($.cookie("keepWriteOpen")) {
    		if($("#thought_log").is(":hidden")) $('#toggle_write').click();
		}
		
		$("#thought_log").draggable({containment:"#wrapper"});
		
		//updateTagsInTabs();
	}
	
	function slimscrollForThoughts() {
			
		$('#articles .text_container').slimScroll({
			height:$('#articles .text_container').height(),
			size:8,
			railVisible: true,
			disableFadeOut: true,
			wheelStep: 2
		}).bind('slimscroll', function(e, pos){
			if(pos == 'bottom') {
				return false;
			}
		});
	}
	
	function destroySlimScroll($element) {
		$element.slimScroll({
		    destroy:true
		});
		
		events = jQuery._data( $element[0], "events" );
		
		if (events) {
		    jQuery._removeData( $element[0], "events" );
		}	
	}
	
	function updateThought($thought,text,tags) {
		$thought.attr('tags',tags.replace(',',' '));
		$thought.find('.text_container p').html(text.replace(/\n\r?/g, '<br />'));
		$thought.find('.list').text(tags);
	}
  	
  	function loadTags() {
  		$("#menu_tags").html($("#menu_tags_hidden").html());
  	}
  	
  	function updateTagsInTabs() {
  		var $newMenuTags = $("<div id='new_menu_tags'></div>");
		var selectedTag = window.GET['tag'];
		var allTags = '';
		
		$("#articles .article").each(function(){
			var tags = $(this).attr('tags');
			if(tags) allTags = tags + ' ' + allTags;
		});
	
		var allTagsArray = getUnique(allTags.split(" "));		
	
		$.each(allTagsArray,function(index,tag){
			if(tag != "") {
				var liElement = createTagElement(tag, getTabURL(tag), (tag == selectedTag));
				$newMenuTags.append(liElement);
			}
		});
	
		var tag = 'Show-All'; 
		var showAll_liElement = createTagElement(tag, getTabURL(tag), (tag == selectedTag || !selectedTag));
		$newMenuTags.append(showAll_liElement);
		$('#menu_tags').html($newMenuTags.html());
		$newMenuTags.remove();
	}
	  	
	function createTagElement(tag, url, isSelected) {
		var classes = "tag menu_tag show_all_tags" + (isSelected ? ' selected' : '');
		return $(document.createElement('li')).addClass(classes).append($(document.createElement('a')).text(tag).attr('href', url));
	}
		
  	function del(id) {
	  	if(!id) return false;
		
		$.ajax({
			url: 'Home',
	 	    type: 'POST',
	 	    data: "tid=" + id + "&action=delete" + "&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	$("#container .data_action").hide();
		    	$("#thought_" + id).remove();
		    	var url = $('#articles .article').length ? window.location.href : (window.location.origin + window.location.pathname);
		    	window.location = url;
	      	}
		});
  	}
  	
  	function updateVisibility(thoughtID,visible) {
  		if(!thoughtID) return;
  		if(typeof(visible) != 'boolean') return;
  		
  		if(visible) visible = 1;
  		else visible = 0;
  		
  		$.ajax({
			url: 'Home',
	 	    type: 'POST',
	 	    data: "visible=" + visible + "&action=update" + "&tid=" + thoughtID + "&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    
	    	}
		});
  	}
  	
	function write(title, text, tag) {
		
		if(!text) {
			showError("Please write something before trying to save...");
			return false;
		}
		
		var action = "add";
		var thoughtID = parseInt($("#thought_id").val());
		if(thoughtID) action = "update";
		
		$.ajax({
			url: 'Home',
	 	    type: 'POST',
	 	    data: "title=" + title + "&text=" + text + "&tags=" + tag + "&action=" + action + "&tid=" + thoughtID + "&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	$('#write').attr('disabled',true);
		    	window.location = window.location.href;
		    	/*
		    	updateThought($("#thought_" + thoughtID),text,tag);
		    	loadSummaryViewAndBindEvents();
		    	editCleanUp();
	    		*/
	    	}
		});
	}
	
	function editCleanUp() {
		$("#text").val("");
		$("#tags").val("");
        $("#thought_id").val("0");
        $("#articles .article").removeClass("editing");
	}
	
	function getTabURL(tag) {
		var delimiter = (window.location.search.replace(/&?(\?|&)tag=([^&]$|[^&]*)/i, "")) ? '&' : '?';
		return window.location.href.replace(/&?(\?|&)tag=([^&]$|[^&]*)/i, "") + delimiter + "tag=" + tag.replace(' ','-');
	}
	
	function loadOlder(){
		if(!window.homeLazyLoad) {
			return false;
		}
		
		var limit = 50;
		if($('#articles .thought').length < limit) return false;
		
		window.homeLazyLoad = false;
		
		var selectedTag = ($('#selected_tag').val() == 'Show-All') ? '' : $('#selected_tag').val();
		var userIDParam = $('#tuid').length ? "&uid=" + $('#tuid').val() : "";
		
		$.ajax({
			url: 'Home',
			type: 'POST',
			data: "tid=" + $('#articles .article:last').attr('id').replace('thought_','') + "&tag=" + selectedTag + "&num=" + limit + "&isPublicPage=" + ($('#home_page').length ? 0 : 1) + userIDParam + "&action=getxbeforey&isAjax=1",
			async: false,
			cache: false,
			timeout: 30000,
			success:function(response) {
				if(response) {
					$("#articles").append(response);
					if($('#articles .thought').length % 50 == 0) {
						window.homeLazyLoad = true;
					}
				}
				slimscrollForThoughts();
				createEvents();
				//setTimeout(function(){window.homeLazyLoad = true;},2000);
			}
		});
	}
	
	function createEvents() {
		
		$(".edit_thought").click(function(){
			
			if($("#toggle_write").is(":visible")) $("#toggle_write").click();
			
			editCleanUp();
			var tid = $(this).parent().addClass('editing').attr("id").replace("thought_","");
			
			$.ajax({
				dataType: 'json',
				url: 'Home',
		 	    type: 'POST',
		 	    data: "tid=" + tid + "&action=get&isAjax=1",
		   	    async: false,
		  	    cache: false,
		 	    timeout: 30000,
			    success: function(response) { 
					$("#title").val(response.title);
					$("#text").val(response.text);
					$("#tags").val(response.tags);
					$("#thought_id").val(response.thoughtID);
		    	}
			});
		});
		
		$("#articles .visibility").change(function(){
			var tid = $(this).parents('.article').attr('id').replace('thought_','');
			var visibility = $(this).is(':checked');
			updateVisibility(tid,visibility);
		});
		
		$("#articles .delete_thought").click(function(){
			//var id = $(this).attr('tid');
			//del(id);
			showDeleteConfirmation($(this).attr('tid'));
		});
	
		$("#toggle_write").click(function() {
			$(this).hide();
			$('#thought_log').fadeIn(function(){				
				if($('#tabs').length && ($('#selected_tag').val() != 'Show-All')) {
					if(!$('#tags').val()) $('#tags').val($('#selected_tag').val() + ',');
				}
			});
		});
	}
		
	$('#thought_log .keep_write_open input').click(function() {
		if($(this).is(':checked')) {
			$.cookie("keepWriteOpen",1);
		} else {
			$.removeCookie("keepWriteOpen");
		}
	});
	
	$("#toggleMoodLog").click(function(){
		var toggleButton = $(this);
		toggleButton.fadeOut().next("span").fadeOut();
		
		$("#moodLog").toggle(function(){
		
			if($(this).is(":visible")) {
				toggleButton.text("Don't Fill In My Mood");
			} else { 
				toggleButton.text("Fill In My Mood");
				toggleButton.next("span").fadeIn();
			}
			
			toggleButton.fadeIn();
		});
	});
	
	$("#thought_log .hide").click(function() {
		$("#thought_log").fadeOut(function(){
			$("#toggle_write").show();
			editCleanUp();
		});
	});
	
	$("#write").click(function(){	
		var text = $("#text").val();
		var tag = $("#tags").val();
		var title = $("#title").val();
		write(title, text, tag);
	});
	
	function showDeleteConfirmation(thoughtID) {
		$("#dialog-confirm").text("Are you sure you want to permanently delete this thought? This action is not reversible.");

	    // Define the Dialog and its properties.
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        modal: true,
	        title: "Confirmation",
	        height: 150,
	        width: 400,
	        draggable: false,
	        buttons: {
				"Cancel": function () {
					$(this).dialog('close');
					callback(thoughtID,false);
				},
				"Delete": function () {
				 	$(this).dialog('close');
					callback(thoughtID,true);
				}
	        }
	    });
	}
	
	function callback(thoughtID,value) {
	    if (value) {
			del(thoughtID);
	    }
	    $("#dialog-confirm").text("");
	}
});