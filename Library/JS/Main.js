$(function() {
	$(document).tooltip();
	$.cookie("previousUrl", $.cookie("currentUrl"));
	$.cookie("currentUrl", window.location.href);
	window.GET = getQueryParams(document.location.search);
});

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