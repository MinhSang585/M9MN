showPageContent=function(page){
	if(agreePage && !agreePress){
		alert(agreeMsg);
	}else{
		$.ajax({
			type: 'POST',
			url: 'tp/'+page,
			data: postData
		}).success(function(data){
			if(page != 'index'){
				var currentUrl = window.location.href;
				var pageUrl = currentUrl.substring(0, currentUrl.lastIndexOf("/"));
				if (currentUrl.indexOf('.html') > -1) {
					History.pushState(null, null , page + '.html');
				} else {
					History.pushState(null, null , page);	
				}
				
			}
			$("#mainContent").html(data);
		}).error(function(res) {
			$("#mainContent").html(res.responseText);
		});
	}
}
close_popup=function(){
	$("#masklayer").fadeOut("fast");
	$("#popupLayer").fadeOut("fast");
}
show_popup=function(content,width,topPosition){
	$("#masklayer").fadeIn("fast");
	$("#masklayer").css('left', "0px");
	$("#masklayer").css('top', "0px");
	$("#popupLayer").css("background-color", "rgba(0,0,0,0.7)");
	$("#popupLayer").css("width", width+"px");
	$("#popupLayer").css("height", "auto");
	$("#popupLayer").css("padding", "20px");
	$("#popupLayer").css("border", "none"); 
	$("#popupLayer").html(content);
	$("#popupLayer").fadeIn("fast");
	if (typeof topPosition !== 'undefined' && topPosition != '' && !isNaN(topPosition)) {
		var top = topPosition;
	} else {
		var top = Math.max($(window).scrollTop() + $(window).height() / 2 - $("#popupLayer")[0].offsetHeight / 2, 0);
	}
	var left = Math.max($(window).scrollLeft() + $(window).width() / 2 - $("#popupLayer")[0].offsetWidth / 2, 0);
	$("#popupLayer").css('top', top + "px");
	$("#popupLayer").css('left', left + "px");
}
show_proccessing=function(content, customWidth){
	var defaultWidth = 200;
	if (typeof customWidth == 'number') {
		defaultWidth = customWidth;
	}
	var str = '';
	if(content != '' && content != 'undefined' && content != null){ 
		if(content == 'logging'){
			str = '<table class="defLogging"><tr><td style="color:white;text-align:center;vertical-align:middle;padding:20px 20px 10px 0;" nowrap><p>'+logging_cap+'</p></td><td style="text-align:center;vertical-align:middle;"><img src="t/default/images/loading_white.gif" style="width:40px!important;height: 40px!important;"></td></tr></table>';
		}else{
			str = content;
		}
	}else{
		str = '<table class="defProcessing"><tr><td style="color:white;text-align:center;vertical-align:middle;padding:20px 20px 10px 0;"><p>'+processing_cap+'</p></td><td style="text-align:center;vertical-align:middle;"><img src="t/default/images/loading.gif"></td></tr></table>';
	}
	show_popup(str,defaultWidth);
}
showAlertMsg=function(title, msg, cb){
	if(msg != '' && msg != 'undefined' && msg != null){
		$("#alertMsg").html('');
		$("#alertMsg").html(msg);
		$("#alertMsg").dialog({
			title: title, 
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
					if (cb !== null && typeof cb === 'function') {
						cb();
					}
				}
			}
		});
	}
}
showAlertMsg2=function(title, msg, url){
	if(msg != '' && msg != 'undefined' && msg != null){
		$("#alertMsg").html('');
		$("#alertMsg").html(msg);
		$("#alertMsg").dialog({
			title: title, 
			modal: true,
			buttons: {
				Ok: function() {
					location.href = url;
				}
			}
		});
	}
}