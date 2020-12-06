function removeParam(key, sourceURL) {
	var rtn = sourceURL.split("?")[0],
		param,
		params_arr = [],
		queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
		console.log(queryString);
	if (queryString !== "") {
		params_arr = queryString.split("&");
		for (var i = params_arr.length - 1; i >= 0; i -= 1) {
			param = params_arr[i].split("=")[0];
			if (param === key) {
				params_arr.splice(i, 1);
			}
		}
		rtn = rtn + "?" + params_arr.join("&");
	}
	console.log(rtn);
	return rtn;
}
function ajaxRequest(url,type){
	var xhttp = new XMLHttpRequest();
	xhttp.open(type, url, true);
	//xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send();
}

$(document).ready(function() {
	$('a[rel=popover]').popover({
		html: true,
		trigger: 'hover',
		placement: 'right',
		content: function () {
			return '<img class="preview-product-image" src="' + $(this).data('img') + '" />';
		}
	});
});