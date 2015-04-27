$(function() {
	
	window.scrollTo(0,0);
	Myndlog.homeLazyLoad = true;
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height()-100) {
			loadOlder();
		}
	});
	
	if($('#header .page_name').val() != 'Thought') {
		fetchSummaryForTag($('#selected_tag').val());
	}
	
	function loadSummaryViewAndBindEvents() {		
		createEvents();
		
		// keep write box open if cookie is set
		if($.cookie("keepWriteOpen")) {
    		if($("#thought_log").is(":hidden")) $('#toggle_write').click();
		}
		
		$("#thought_log").draggable({containment:"#wrapper"});
		
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
		
		var selectedTagName = $('#selected_tag').val();

		$.ajax({
			dataType: 'json',
			url: getURL('Home'),
	 	    type: 'POST',
	 	    data: "tid=" + id + "&action=delete" + "&selectedTagName=" + selectedTagName + "&paint_tabs=1&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	$("#thought_" + id).remove();
		    	$('#tabs').replaceWith(response.tabs);
		    	if(!$('#articles .article').length) {
		    		window.location = window.location.href;
		    	}
	      		createEvents();
	      	}
		});
  	}
  	
  	function updateVisibility(thoughtID,visible) {
  		if(!thoughtID) return;
  		if(typeof(visible) != 'boolean') return;
  		
  		if(visible) visible = 1;
  		else visible = 0;
  		
  		$.ajax({
			url: getURL('Home'),
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
		
		// commented cause we only allow 1 tag now
		/*
		// clean up tags
		var newTag;
		var continueLoop = true;
			do {
				newTag = tag.replace(',,',',')
				continueLoop = (newTag!=tag);
				tag = newTag;
			} while(continueLoop);
		if(tag.substring(0,1) == ',') tag = tag.substring(1);
		if(tag.substring(tag.length-1) == ',') tag = tag.substring(0,tag.length-1);
		*/
		
		if(!tag) {
			showError("Please add a tag");
			return false;
		}
		
		if(!(/^[a-z0-9]+$/i).test(tag)) {
			showError("Invalid tag. Only letters(a-z) and digits(0-9) allowed.");
			return false;
		}
		
		var thoughtID = parseInt($("#thought_id").val());
		var action = thoughtID ? 'update' : 'add';
		var selectedTagName = $('#selected_tag').val();
		var view_action = thoughtID ? 'thought_replace' : 'prepend';
		
		$.ajax({
			dataType: 'json',
			url: getURL('Home'),
	 	    type: 'POST',
	 	    data: "title=" + title + "&text=" + text + "&tags=" + tag + "&action=" + action + "&tid=" + thoughtID + "&selectedTagName=" + selectedTagName + "&view_action=" + view_action + "&paint_tabs=1&paint_new_thought=1&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
		    	    	
	    	var thoughtBelongsToSelectedTab = false;
	    	/*
	    	if(selectedTagName == "Show-All" || $.inArray(selectedTagName,$('#tags').val().split(',')) >= 0) {
	    		thoughtBelongsToSelectedTab = true;
	    	}
	    	*/
	    	
	    	paintSummary(response);
	    	
	    	/*
	    	if(thoughtID) {
	    		if(thoughtBelongsToSelectedTab) {
	    			$('#thought_' + thoughtID).replaceWith(response.thought);
	    		} else {
	    			$('#thought_' + thoughtID).remove();
	    		}
	    	} else {
	    		$('#articles .message').remove();
	    		if(thoughtBelongsToSelectedTab) {
	    			$('#articles').prepend(response.thought);
	    		}
	    	}
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
		
		var limit = window.tpq;
		if($('#articles .thought').length < limit) return false;

		Myndlog.homeLazyLoad = false;
		
		var selectedTag = $('#selected_tag').val();
		
		var userIDParam = $('#tuid').length ? "&uid=" + $('#tuid').val() : "";
		var pageName = $('#header .page_name').val();
		
		if(pageName == 'Page' && !selectedTag) {
			console.log(pageName);
			console.log(selectedTag);
			return false;
		}
		
		$.ajax({
			dataType: 'json',
			url: getURL('Home'),
			type: 'POST',
			data: "tid=" + $('#articles .article:last').attr('id').replace('thought_','') + "&tag=" + selectedTag + "&num=" + limit + "&isPublicPage=" + ($('#home_page').length ? 0 : 1) + userIDParam + "&page_name=" + pageName + "&view_action=append" + "&action=getxbeforey&isAjax=1",
			async: false,
			cache: false,
			timeout: 30000,
			success:function(response) {
				paintSummary(response);
				if(response.thoughts.count >= window.tpq) {
					Myndlog.homeLazyLoad = true;
					console.log($('#articles .article:last').attr('id').replace('thought_',''));
				}
			}
		});
	}
	
	$('#search .clear_search').on('click', function(){
		fetchSummaryForTag();
		$(this).hide();
	});
	
	function createEvents() {
		
		$('#tabs').on('mouseover', function() {
			if(Myndlog.minMargin >= 0) return;
			$('#tabs .scroller').show();
		});
		
		$('#tabs').on('mouseleave', function() {
			if(Myndlog.minMargin >= 0) return;
			$('#tabs .scroller').hide();
		});
		
		$('#wrapper a.fetch_summary').click(function(){
			if(typeof $(this).attr('inline-href') !== 'undefined') {
				window.history.pushState("object or string", "Title", $(this).attr('inline-href'));
			}
			fetchSummaryForTag($(this).text());
		});
		
		$('#articles .bottom input[type=checkbox]').prop('disabled',false);
		
		$('#articles .text_container .title, #articles .open_article').on('click',function(){
			if(typeof $(this).attr('href') == 'undefined' || $(this).attr('href') == 'home') {
				if($("#toggle_write").is(":visible")) $("#toggle_write").click();
			
				editCleanUp();
				var tid = $(this).parents('.article').addClass('editing').attr("id").replace("thought_","");
	
				$.ajax({
					dataType: 'json',
					url: getURL('Home'),
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
			} else {
				window.location = $(this).attr('href');
			}
		});
		
		$("#articles .visibility").change(function(){
			if(!$(this).parents('.article').find('.title').length) {
				showError('Articles without a title cannot be made public. Please add a title to the article.');
				$(this).attr('checked',false);
				return;
			}
		
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
					// .val() + ','); modified as we only allow 1 tag now.
					if(!$('#tags').val()) $('#tags').val($('#selected_tag').val());
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
	
	$('#tags').keyup(function(e){
		if(e.keyCode == 13) {
		   $("#write").click(); 
		}
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
					deleteCallback(thoughtID,false);
				},
				"Delete": function () {
				 	$(this).dialog('close');
					deleteCallback(thoughtID,true);
				}
	        }
	    });
	}
	
	$("#search .button").on('click',function(){
		fetchSummaryForSearch($("#search .box").val());
	});
	
	function deleteCallback(thoughtID,value) {
	    if (value) {
			del(thoughtID);
	    }
	    $("#dialog-confirm").text("");
	}
	
	function fetchSummaryForSearch(keyword) {
		if(typeof keyword == 'undefined' || keyword == '') return;
		
		$.ajax({
			dataType: 'json',
			url: getURL('Home'),
	 	    type: 'POST',
	 	    data: "q=" + keyword + "&tuid=" + $('#tuid').val() + "&action=search&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {
				paintSummary(response);
				$('#search .clear_search').show();
				loadSummaryViewAndBindEvents();
				Myndlog.homeLazyLoad = true;
			}
		});
	}

	function fetchSummaryForTag(tagName) {
		if(typeof tagName == 'undefined') tagName = '';
		var pageName = $('#header .page_name').val();
		
		$.ajax({
			dataType: 'json',
			url: getURL('Home'),
	 	    type: 'POST',
	 	    data: "selectedTagName=" + tagName + "&tuid=" + $('#tuid').val() + "&ipp=" + ($('#home_page').length ? 0 : 1) + "&page_name=" + pageName + "&action=summary&isAjax=1",
	   	    async: false,
	  	    cache: false,
	 	    timeout: 30000,
		    success: function(response) {		    		    	
				paintSummary(response);
				Myndlog.homeLazyLoad = true;
			}
		});
	}

	function paintSummary(response) {
		paintTabs(response.tabs);
		paintThoughts(response.thoughts);

		loadSummaryViewAndBindEvents();
		editCleanUp();
				
		if(!$('#articles .article').length) {
			//window.location = window.location.href;
		}
	}
	
	function paintTabs(tabs) {
		if(typeof tabs === 'undefined') return;
		
		if(!$('#tabs').length) {
			$('#summary').prepend($("<div id='tabs'></div>"));
		}
		$('#tabs').replaceWith(tabs);
	}
	
	function paintThoughts(thoughts) {
		if(typeof thoughts.thought_replace !== 'undefined') {
			$('#thought_' + thoughts.thought_id).replaceWith(thoughts.thought_replace);
		}

		if(typeof thoughts.replace !== 'undefined') {
			$('#articles').replaceWith(thoughts.replace);
		}
		
		if(typeof thoughts.append !== 'undefined') {
			$('#articles').append(thoughts.append);
		}
		
		if(typeof thoughts.prepend !== 'undefined') {
			$('#articles').prepend(thoughts.prepend);
		}
	}
});