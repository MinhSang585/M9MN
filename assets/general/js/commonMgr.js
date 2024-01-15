getCCL = function (id) {
	$.ajax({
		type: "POST",
		url: "a/getCCL",
		dataType: "json",
	}).success(function (data) {
		if (data.status == "0") {
			$("#" + id).html(data.d.return_ccl.all["ccl"]);
		}
	});
};
loginUser = function (fName, showAgree) {
	if (mobileView == "m") {
		show_proccessing("logging");
	}
	$.ajax({
		type: "POST",
		url: "a/login",
		data: $("#" + fName).serialize(),
		dataType: "json",
	}).success(function (data) {
		var paths = location.pathname.substring(1).split("/");
		var m = paths[0].trim().length == 2 && paths[0].trim().toLowerCase() != '4d'? paths[0].trim() + "/" : "";
		var link = "/" + m;

		if (data.status != "0") {
			alert(data.msg);
		} else {
			if (ui_method == 2) {
				if (showAgree == "1") {
					document.location.href = "agree.html";
				} else {
					document.location.href = link;
				}
			} else {
				if (showAgree == "1") {
					showPageContent("agree");
				} else {
					showPageContent("index");
				}
			}
		}
		if (mobileView == "m") {
			close_popup();
		}
	});
	return false;
};
logoutUser = function () {
	$.ajax({
		type: "POST",
		url: "a/logout",
		dataType: "json",
	}).success(function (data) {
		var paths = location.pathname.substring(1).split("/");
		var m = paths[0].trim().length == 2 && paths[0].trim().toLowerCase() != '4d'? paths[0].trim() + "/" : "";
		var link = "/" + m;

		agreePage = false;
		if (ui_method == 2) {
			document.location.href = link;
		} else {
			showPageContent("index");
		}
	});
};
triggerSave = function (cType, fName) {
	$.ajax({
		type: "POST",
		url: "a/set" + cType,
		data: $("#" + fName).serialize(),
		dataType: "json",
	}).success(function (data) {
		if (data["d"]["error"] != "") {
			alert(data["d"]["error"]);
		} else {
			if (data.status == "0") {
				if (data["d"]["page"] == "changePassword") {
					editLinkage(data["d"]["page"], data["d"]["editid"]);
				} else {
					showPopUpContent(cType);
				}
			}
		}
	});
};
showPopUpContent = function (cType, width, topPosition = "") {
	$.ajax({
		type: "POST",
		url: "tp/" + cType,
		data: { theme: theme, ap: "1" },
		dataType: "html",
	}).success(function (data) {
		// alert(data);
		show_popup(data, width, topPosition);
	});
};
getAnnouncement = function (id, pop, lang) {
	$.ajax({
		type: "POST",
		url: "a/getAnnouncement?" + lang,
		data: { lang: lang, pop: pop },
		dataType: "json",
	}).success(function (data) {
		// alert(data.d.announcement);
		if (data.status == "0") {
			if (pop == "1") {
				executeFunctionByName(id, window, data.d.announcement);
			} else {
				$("#" + id).html(data.d.announcement);
			}
		}
	});
};
executeFunctionByName = function (functionName, context /*, args */) {
	var args = [].slice.call(arguments).splice(2);
	var namespaces = functionName.split(".");
	var func = namespaces.pop();
	for (var i = 0; i < namespaces.length; i++) {
		context = context[namespaces[i]];
	}
	return context[func].apply(this, args);
};

//add thousand separator
numberWithCommas = function (n) {
	var parts = n.toString().split(".");
	return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
};

//create form and submit
submitForm = function (action, target, dataArr) {
	var hiddenField;
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", action);
	if (target != "") {
		form.setAttribute("target", target);
	}
	$.each(dataArr, function (key, value) {
		hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", key);
		hiddenField.setAttribute("value", value);
		form.appendChild(hiddenField);
		document.body.appendChild(form);
	});
	form.submit();
};
getGenericData = function (actionName, postData, callback) {
	$.ajax({
		type: "POST",
		url: "a/" + actionName,
		data: postData,
		async: async,
	}).success(function (data) {
		window[callback](data);
	});
};

getUnreadMessage = function (mid) {
	if (mid == undefined) mid = "mailbox";
	if (loginFlag) {
		$.ajax({
			type: "POST",
			url: "a/getPrivateMessage",
			data: { action: "unread" },
			dataType: "json",
		}).success(function (data) {
			if (data.status == "0") {
				unreadCount = data.d.unread;
				if (unreadCount > 0) {
					$("#" + mid).html('<div class="mailunread"><a href="myinbox.html" style="color:#ffffff;">' + unreadCount + '<span class="inboxunread">&nbsp;</span></a></div>');
				} else {
					$("#" + mid).html('<a href="myinbox.html"><div class="inboxread">&nbsp;</div></a>');
				}
			}
		});
	}
};
checkAjaxStatus = function (ErrMsg) {
	var urlCheckArray = ["a/makeBDeposit", "a/makePDeposit", "a/makeBDepositV2", "a/makeBDepositV3", "a/makeBDeposit_test"];
	$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
		if (jqxhr.status == "403") {
			if (urlCheckArray.indexOf(settings.url) > -1) {
				if (window["close_popup"] !== undefined) close_popup();
				alert(ErrMsg);
			}
		}
	});
};

_fmg = function (g, postdata, cb) {
	var id = "miniTokenNo";
	$e = $("#" + id);

	if (cb == undefined) {
		cb = function (data) {
			if (typeof data.d.tickets !== "undefined") {
				$e.html(data.d.tickets);
			}
		};
	}

	if (postdata == undefined) {
		postdata = {
			custom: {
				no_winner: 1,
			},
		};
	}

	postdata.game = g;

	$.ajax({
		type: "POST",
		url: "a/getMinigameInfo",
		dataType: "json",
		data: postdata,
		success: cb,
	});
};
