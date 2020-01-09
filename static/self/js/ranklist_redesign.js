
probBeginIdx = 4;
acClass = 'accepted';
fbClass = 'fb';
rjClass = 'rejected';
contestPending = {
	text: 'Pending',
	css: 'pe-text',
	code: 0
};
contestRunning = {
	text: 'Running',
	css: 'accept-text',
	code: 1
};
contestEnded = {
	text: 'Ended',
	css: 'wrong-text',
	code: 2
};
serverTimeRequestUrl = '/DO_NOT_DELETE.for_ajax_time.empty';
lsKey = {
	ranklist_auto_refresh: ''
};
ranklistAutoRefreshItv = 30;

function getServerTimestamp() {
	return Date.parse(new Date($.ajax({async: false, url: serverTimeRequestUrl, cache: false}).getResponseHeader("Date")));
}

function timeStrToTimestamp(str) {
	return Date.parse(new Date(str));
}

function preZeroFill(num, size) {
	if(num >= Math.pow(10, size))
		return num.toString();
	else {
		var str = Array(size + 1).join('0') + num;
		return str.slice(str.length - size);
	}
}

function secondToTimeStr(sec) {
	var h = parseInt(sec / 3600);
	sec %= 3600;
	var m = parseInt(sec / 60);
	sec %= 60;
	var s = parseInt(sec);
	if(sec < 0) return '--';
	else return h + ":" + preZeroFill(m, 2) + ":" + preZeroFill(s, 2);
}

function timeStrToSecond(timeStr) {
	var tmp = timeStr.split(':');
	var h = parseInt(tmp[0]);
	var m = parseInt(tmp[1]);
	var s = parseInt(tmp[2]);
	return h * 3600 + m * 60 + s;
}

function setContestTimeStatus() {
	var timeRemaining = contestLength - timeElapsed;
	$("#time-elapsed").text(secondToTimeStr(timeElapsed));
	$("#time-remaining").text(secondToTimeStr(timeRemaining));

	var progress = timeElapsed / contestLength * 100;
	$("#contest-progress").attr("aria-valuenow", progress);
	$("#contest-progress").find("span").text(progress + '%');
	$("#contest-progress").css("width", progress + '%');
}

function refreshContestTime() {
	$("#contest-progress").everyTime('1s', 'contestTimer', function () {
		timeElapsed++;
		setContestTimeStatus();
		if(timeElapsed >= contestLength) {
			$(this).stopTime();
			$(this).oneTime('1s', function () {
				window.location.reload();
			});
		}
	}, 0);
}

function setContestPendingTime() {
	$("#contest-pending-timer").text(secondToTimeStr(pendingTime));
}

function refreshContestPendingTime() {
	$("#contest-pending-timer").everyTime('1s', 'contestPendingTimer', function () {
		pendingTime--;
		setContestPendingTime();
		if(pendingTime <= 0) {
			$(this).stopTime();
			$(this).oneTime('1s', function () {
				window.location.reload();
			});
		}
	}, 0);
}

function ranklistRedesign() {
	var probNum = $("#ranklist-table thead tr:first").find("th").length - probBeginIdx;
	// Calculate FB and solved count
	for(var i = 0; i < probNum; ++i) {
		var minTime = Number.MAX_SAFE_INTEGER;
		var solvedCount = 0;
		var totalCount = 0;
		$("#ranklist-table tbody tr").each(function () {
			var td = $(this).find("td").eq(probBeginIdx + i);
			if(td.hasClass(acClass)) {
				solvedCount++;
				var now_time = timeStrToSecond(td.find(".detail-time").text());
				minTime = Math.min(minTime, now_time);
			}
			if(td.hasClass(acClass) || td.hasClass(rjClass))
				totalCount += parseInt(td.data("subm-total"));
		});
		// Set FB
		$("#ranklist-table tbody tr").each(function () {
			var td = $(this).find("td").eq(probBeginIdx + i);
			if(td.hasClass(acClass)) {
				var now_time = timeStrToSecond(td.find(".detail-time").text());
				if(now_time === minTime) {
					td.removeClass(acClass);
					td.addClass(fbClass);
				}
			}
		});
		// Set count
		//$("#ranklist-table thead tr:last").append('<th class="thead-bottom text-mid-bold">' + solvedCount + ' / ' + totalCount + '</th>');
	}
}

function initContest() {
	startTime = timeStrToTimestamp($("#start-time").text());
	endTime = timeStrToTimestamp($("#end-time").text());
	nowTime = getServerTimestamp();
	timeElapsed = parseInt((Math.min(Math.max(nowTime, startTime), endTime) - startTime) / 1000);
	contestLength = parseInt((endTime - startTime) / 1000);
	if(nowTime < startTime) {
		contestStatus = contestPending;
		setContestTimeStatus();
		$("#ranklist").remove();
		$("#contest-pending-timer").show();
		pendingTime = parseInt((startTime - nowTime) / 1000);
		setContestPendingTime();
		refreshContestPendingTime();
	}
	else if(nowTime < endTime) {
		contestStatus = contestRunning;
		setContestTimeStatus();
		refreshContestTime();
		ranklistRedesign();
	}
	else {
		contestStatus = contestEnded;
		$("#contest-progress").css('transition', 'none', 'color', '-webkit-transition', 'none', '-o-transition', 'none');
		setContestTimeStatus();
		ranklistRedesign();
	}
	$("#contest-status").text(contestStatus["text"]);
	$("#contest-status").addClass(contestStatus["css"]);
}

function setAutoRefresh() {
	$("#auto-refresh-buttons *").removeClass("active");
	if(autoRefresh == 0) {
		$("#auto-refresh-off").addClass("active");
		$("#ranklist-table").stopTime();
	}
	else {
		$("#auto-refresh-on").addClass("active");
		$("#ranklist-table").stopTime();
		$("#ranklist-table").oneTime(ranklistAutoRefreshItv + 's', function () {
			window.location.reload();
		});
	}
}

$(function () {
	// Back to top
	function backToTopToggle() {
		if($(window).scrollTop() > 360)
			$("#fb-back-to-top").fadeIn(250);
		else $("#fb-back-to-top").fadeOut(250);
	}
	$(window).scroll(function () {
		backToTopToggle();
	});
	$("#fb-back-to-top").click(function () {
		$.scrollTo("body", 1000);
		return false;
	});

	// Export ranklist
	$("#export-ranklist").click(function () {
		var instance = $("#ranklist-table").tableExport({
			formats: ["xlsx"],
			exportButtons: false,
			trimWhitespace: false,
			filename: 'ranklist_result'
		});
		instance.types.date.assert = function (v) {
			return false;
		};
		var exportData = instance.getExportData()["ranklist-table"]["xlsx"];
		instance.export2file(exportData.data, exportData.mimeType, exportData.filename, exportData.fileExtension);
	});

	// Auto Refresh
	autoRefresh = 0;
	if(window.localStorage) {
		var ls = window.localStorage;
		// Restore Contest Last Problem
		autoRefresh = ls.getItem(lsKey["ranklist_auto_refresh"] + '-c' + cid);
		if(autoRefresh == null) autoRefresh = 0;
	}
	setAutoRefresh();
	$("#auto-refresh-buttons *").click(function() {
		if($(this).hasClass("active")) return;
		autoRefresh = $(this).attr("data-value");
		if(window.localStorage) {
			var ls = window.localStorage;
			ls.setItem(lsKey["ranklist_auto_refresh"] + '-c' + cid, autoRefresh);
		}
		setAutoRefresh();
	});
});
