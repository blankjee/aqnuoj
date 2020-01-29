var Base = {};

Base.post = function (url, data, success, error) {
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType:'JSON',
        success: success,
        error: error || function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 400) {
                Base.alertError('没有权限操作');
                return;
            }
            console.error("The following error occurred: " + textStatus, errorThrown);
        }
    });
};

function gotopage(inputid){
    var id = '#' + inputid;
    page = $(id).val();
    if (!page) {
        page = 1;
    }
    var path = window.parent.location.href;
    if (path.indexOf('page=') == -1){
        var laststr = path.substring(path.length - 3);
        if (laststr != 'php'){
            path = path + ("&page=" + page);
        } else {
            path = path + ("?page=" + page);
        }
    }else{
        path = path.replace(/&*&page=\d*$/, '&page='+page);
        path = path.replace(/&*\?page=\d*$/, '?page='+page);
    }
    window.location.href = path;
}
