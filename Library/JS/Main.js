$(function() {

	$(window).scroll(function() {
		if($('#tabs').length) {
			var height = parseInt($('#tabs').height());
			var margin = parseInt($('#articles').css('margin-top'));
			
			if(needFixedTabs()) {
				$('#tabs').addClass('tabs_fixed');
				$('#articles').css('margin-top',(margin + height));
			} else if($(window).scrollTop() == 0 && $('#tabs').hasClass('tabs_fixed')) {
				$('#tabs').removeClass('tabs_fixed');
				$('#articles').css('margin-top',(margin - height));
			}
		}
	});
	
	window.Myndlog = {};
	Myndlog.margins = {
		name:function() {
			return [$('#tuid').val(), (isDev() ? 'd' : 'p'), $('#header .page_name').val()].join('_');
		},
		set:function() {
			$.cookie(Myndlog.margins.name(), parseInt($('#menu_tags').css('margin-left')));
			
			if(parseInt($('#menu_tags').css('margin-left')) >= 0) $("#tabs .left_scroller").parent().hide();
			if(parseInt($('#menu_tags').css('margin-left')) == Myndlog.minMargin) $("#tabs .right_scroller").parent().hide();
		},
		get:function() {
			return $.cookie(Myndlog.margins.name());
		}
	}
	
	$(window).on("popstate", function(e) {
    	if (e.originalEvent.state !== null) {
    		window.location.href = location.href;
   	 	}
  	});
});

function needFixedTabs() {
	if($('#tabs').length) {
		var headerPos = $('#tabs').offset().top + $('#tabs').height();
		return ($(window).scrollTop() > headerPos && !$('#tabs').hasClass('tabs_fixed'));
	} else {
		return false;
	}
}

function getErrorMessage(msg) {
	return "<span class='error_symbol' title='" + msg + "'>&#10008</span>";
}

function getUnique(array){
	var u = {}, a = [];
	
	for(var i = 0, l = array.length; i < l; ++i){
		if(u.hasOwnProperty(array[i])) {
		 continue;
		}
		a.push(array[i]);
		u[array[i]] = 1;
	}
	return a;
}

function showError(errorMessage) {
	$("#dialog-confirm").text(errorMessage);

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
    	resizable: false,
        modal: true,
        title: "Error",
        height: 150,
        width: 400,
        draggable: false,
        buttons: {
			"Ok": function () {
				$(this).dialog('close');
			}
        }
    });
}

function getQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }

    return params;
}

function getBaseURL() {
	var url = isDev() ? ("http://localhost/~abhishekgandhi/myndlog") : (window.location.origin);
	return url;
}

function getURL(pageName) {
	var url = isDev() ? ("http://localhost/~abhishekgandhi/myndlog/index.php/" + pageName) : (window.location.origin + '/' + pageName);
	return url;
}

function isDev() {
	return window.location.hostname == 'localhost';
}

function isUserNameIllegal(value) {

	var illegalNames = ['MyndLog', 'SignIn','Register','Home','User','EditProfile','Controller','Mashup','Notepad','Thought','Write','Page','Admin'];
	illegalNames = $.map(illegalNames, function(value, index){ 
											return value.toLowerCase(); 
										});
	
	return $.inArray(value.toLowerCase(), illegalNames) < 0;
}