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
