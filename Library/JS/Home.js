$(function() {
	
	window.scrollTo(0,0);
	Myndlog.homeLazyLoad = true;
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
			
		$('#tabs').on('mouseover', function() {
			if(Myndlog.minMargin >= 0) return;
			$('#tabs .scroller').show();
		});
		
		$('#tabs').on('mouseleave', function() {
			if(Myndlog.minMargin >= 0) return;
			$('#tabs .scroller').hide();
		});
		
		initScrollEvent();
		if(Myndlog.minMargin < 0) {
			bindScrollLeftEvent();
			bindScrollRightEvent();
			var position = true;
			if(Myndlog.margins.get()) {
				$('#menu_tags').css('margin-left',Myndlog.margins.get());
			}
			if($('#menu_tags').length) makeTabVisible($('#menu_tags li.selected'));
		}
	}
	
	/* SCROLL CODE ENDS */
	
	function initScrollEvent() {
		var fullTabsWidth = 0;
		var tabMargin = parseInt($('#menu_tags li').css('margin-right')) + parseInt($('#menu_tags li').css('margin-left'));			
		$('#menu_tags li').each(function(){
			fullTabsWidth += $(this).width() + tabMargin;
		});
		Myndlog.minMargin = $('#menu_tags').width() - fullTabsWidth;
		Myndlog.marginChange = 200;
		
		// DISABLE SCROLL :
		Myndlog.minMargin = 0;
	}
	
	function bindScrollLeftEvent() {
		$("#tabs .left_scroller").on('click',function(){
			var currentMargin = parseInt($('#menu_tags').css('margin-left'));
			var newMargin = currentMargin + Myndlog.marginChange;
			if(newMargin > 0) {
				newMargin = 0;
				$(this).parent().hide();
			}
			$('#menu_tags').animate({'margin-left':newMargin},function(){
				$("#tabs .right_scroller").parent().show();
				Myndlog.margins.set();
			});		
		});
	}
	
	function bindScrollRightEvent() {
		$("#tabs .right_scroller").on('click',function(){
			var currentMargin = parseInt($('#menu_tags').css('margin-left'));
			var newMargin = currentMargin - Myndlog.marginChange;
			if(newMargin < Myndlog.minMargin) {
				newMargin = Myndlog.minMargin;
				$(this).parent().hide();
			}
			$('#menu_tags').animate({'margin-left':newMargin},function(){
				$("#tabs .left_scroller").parent().show();
				Myndlog.margins.set();
			});
		});
	}
	
	function isTabVisible($tab) {
		if(!$tab.length) return;
		
		var tabStartPos = $tab.position().left;
		var isTabHiddenOnTheLeft = (tabStartPos < 0);
		
		var tabEndPos = $tab.position().left + $tab.width() + parseInt($tab.css('margin-left')) + parseInt($tab.css('margin-right'));
		var isTabHiddenOnTheRight = (tabEndPos > $('#menu_tags').width());
		
		var isTabVisble = !(isTabHiddenOnTheLeft || isTabHiddenOnTheRight);
		return isTabVisble;
	}
	
	function makeTabVisible($tab) {
		var tabStartPos = $tab.position().left;
		var isTabHiddenOnTheLeft = (tabStartPos < 0);
		
		var tabEndPos = $tab.position().left + $tab.width() + parseInt($tab.css('margin-left')) + parseInt($tab.css('margin-right'));
		var isTabHiddenOnTheRight = (tabEndPos > $('#menu_tags').width());
		
		var isTabHidden = isTabHiddenOnTheLeft || isTabHiddenOnTheRight;
		
		if(isTabHiddenOnTheLeft) {		
			do {
				var margin = parseInt($('#menu_tags').css('margin-left'));
				$('#menu_tags').css('margin-left',margin+1)
				tabStartPos = $tab.position().left;
				isTabHiddenOnTheLeft = (tabStartPos < 0);
			} while(isTabHiddenOnTheLeft);
			
			var $prevTab = $tab.prev().prev().prev();
			var isTabRightEnough = isTabVisible($prevTab) || parseInt($('#menu_tags').css('margin-left')) >= 0;
			if(!isTabRightEnough) {
				do {
					var margin = parseInt($('#menu_tags').css('margin-left'));
					$('#menu_tags').css('margin-left',margin+1);
					isTabRightEnough = isTabVisible($prevTab) || parseInt($('#menu_tags').css('margin-left')) >= 0;
				} while(!isTabRightEnough);
			}
		}
		
		if(isTabHiddenOnTheRight) {
			do {
				var margin = parseInt($('#menu_tags').css('margin-left'));
				$('#menu_tags').css('margin-left',margin-1)
				tabEndPos = $tab.position().left + $tab.width() + parseInt($tab.css('margin-left')) + parseInt($tab.css('margin-right'));
				isTabHiddenOnTheRight = (tabEndPos > $('#menu_tags').width());
			} while(isTabHiddenOnTheRight);
			
			var $nextTab = $tab.next().next().next();
			var isTabLeftEnough = isTabVisible($nextTab) || parseInt($('#menu_tags').css('margin-left')) <= Myndlog.minMargin;
			if(!isTabLeftEnough) {
				do {
					var margin = parseInt($('#menu_tags').css('margin-left'));
					$('#menu_tags').css('margin-left',margin-1);
					isTabLeftEnough = isTabVisible($nextTab) || parseInt($('#menu_tags').css('margin-left')) <= Myndlog.minMargin;
				} while(!isTabLeftEnough);
			}
		}
		Myndlog.margins.set();
	}
	
	/* SCROLL CODE ENDS */
	
	function slimscrollForThoughts() {
			
		$('#articles .text_container').slimScroll({
			height:$('#articles .text_container').height(),
			size:6,
			railVisible: false,
			disableFadeOut: true,
			wheelStep: 2,
			color: '#555'
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
		
		var action = "save";
		var thoughtID = parseInt($("#thought_id").val());
		
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
		$("#title").val("");
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
		if(!Myndlog.homeLazyLoad) {
			return false;
		}
		
		var limit = 50;
		if($('#articles .thought').length < limit) return false;
		
		Myndlog.homeLazyLoad = false;
		
		var selectedTag = ($('#selected_tag').val() == 'Show-All') ? '' : $('#selected_tag').val();
		var userIDParam = $('#tuid').length ? "&uid=" + $('#tuid').val() : "";
		
		$.ajax({
			url: getURL('Home'),
			type: 'POST',
			data: "tid=" + $('#articles .article:last').attr('id').replace('thought_','') + "&tag=" + selectedTag + "&num=" + limit + "&isPublicPage=" + ($('#home_page').length ? 0 : 1) + userIDParam + "&action=getxbeforey&isAjax=1",
			async: false,
			cache: false,
			timeout: 30000,
			success:function(response) {
				if(response) {
					$("#articles").append(response);
					if($('#articles .thought').length % 50 == 0) {
						Myndlog.homeLazyLoad = true;
					}
				}
				slimscrollForThoughts();
				createEvents();
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