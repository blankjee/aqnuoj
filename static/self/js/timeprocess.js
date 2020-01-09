
function preZeroFill(num, size) {
    if (num >= Math.pow(10, size)) {
        return num.toString();
    }
    else {
        var str = Array(size + 1).join('0') + num;
        return str.slice(str.length - size);
    }
}

function updateTime(total) {
    var h = parseInt(total / 3600);
    var m = parseInt(total % 3600 / 60);
    var s = parseInt(total % 60);
    $("#time-elapsed").text(h + ":" + preZeroFill(m, 2) + ":" + preZeroFill(s, 2));
}

$(function () {
    var timeArr = $("#time-elapsed").text().split(":");
    if (timeArr.length === 3) {
        var h = parseInt(timeArr[0]);
        var m = parseInt(timeArr[1]);
        var s = parseInt(timeArr[2]);
        total = parseInt(h * 3600 + m * 60 + s);
        updateTime(total);
        var timer = window.setInterval(function () {
            total++;
            updateTime(total);
        }, 1000);
    }
});

	