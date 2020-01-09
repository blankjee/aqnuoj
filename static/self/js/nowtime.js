var stime = Date.parse(new Date());
function updatetime(){
    stime += 1000;
    var etime = new Date(parseInt(stime));
    $("#dt").html(etime.toString("Y-m-d H:i:s"));
}
setInterval(updatetime,1000);