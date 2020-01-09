
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
    $("#time-remaining").text(h + ":" + preZeroFill(m, 2) + ":" + preZeroFill(s, 2));
    if (total < 60)
        $("#time-remaining").css('color', 'red');
}

$(function () {
    var timeArr = $("#time-remaining").text().split(":");
    if (timeArr.length === 3) {
        var h = parseInt(timeArr[0]);
        var m = parseInt(timeArr[1]);
        var s = parseInt(timeArr[2]);
        total = parseInt(h * 3600 + m * 60 + s);
        updateTime(total);
        var timer = window.setInterval(function () {
            if (total === 0) {
                window.clearInterval(timer);
                alert("竞赛已结束！")
                window.location.href = "/home/contestlist.php";
            }
            else {
                total--;
                updateTime(total);
            }
        }, 1000);
    }
});

	