/**
 * 管理后台端各个页面的JS方法
 * @author huangjie
 */


/*公用定义*/

indexTr='';       //用来找表格行节点的

toastr.options = {
    "positionClass": "toast-top-right",//弹出窗的位置
    "timeOut": "500",
    "progressBar": "true",
};


/*公告页面*/
//删除
function removeNews(news_id, index){
    indexTr = index;
    $('#hiddenid').val(news_id);//给会话中的隐藏属性ID赋值
    $('#delcfmModel').modal();
}
function submitRemoveNews(contest_id) {
    var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID

    //进行异步删除，并用toaststr提示。
    var data = "table=news&id=" + id;
    Base.post("/admin/tool/delById.php", data,
        function (res) {
            if (res && res.code == 1) {
                toastr.success(res.msg);
                setTimeout(function() {
                    $("#bootstrap-data-table").children('tbody').children('tr').eq(indexTr).remove();
                }, 500);
            } else if (res && res.code == 0) {
                toastr.error(res.msg);
                setTimeout(function() {
                    window.location.href = res.url;
                }, 500);
            }
        });
    return false;
}

/*switch方法*/
//屏蔽公告
$('body').on('click','.chk_de',function(event) {
    var flag;
    if(this.checked) {
        var stt=confirm("确定要屏蔽吗？");
        if(stt) {
            //修改数据库中的Defunct=Y
            var data = "table=news&defunct='Y'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeDefunct.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).attr("checked",true);
            flag=1;
        } else {
            $(this).removeAttr("checked");
        }
    } else {
        var stt=confirm("确定要取消屏蔽吗？");
        if(stt) {
            //修改数据库中的Defunct=N
            var data = "table=news&defunct='N'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeDefunct.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).removeAttr("checked");
            flag=0;
        } else {
            return false;
        }
    }
});
//屏蔽问题
$('body').on('click','.chk_pr',function(event) {
    var flag;
    if(this.checked) {
        var stt=confirm("确定要屏蔽吗？");
        if(stt) {
            //修改数据库中的Defunct=Y
            var data = "table=problem&defunct='Y'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeDefunct.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).attr("checked",true);
            flag=1;
        } else {
            $(this).removeAttr("checked");
        }
    } else {
        var stt=confirm("确定要取消屏蔽吗？");
        if(stt) {
            //修改数据库中的Defunct=N
            var data = "table=problem&defunct='N'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeDefunct.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).removeAttr("checked");
            flag=0;
        } else {
            return false;
        }
    }
});

//置顶公告
$('body').on('click','.chk_im',function(event) {
    var flag;
    if(this.checked) {
        var stt=confirm("确定要置顶吗？");
        if(stt) {
            //修改数据库中的importance=1
            var data = "table=news&importance='1'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeImportance.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).attr("checked",true);
            flag=1;
        } else {
            $(this).removeAttr("checked");
        }
    } else {
        var stt=confirm("确定要取消置顶吗？");
        if(stt) {
            //修改数据库中的importance=0
            var data = "table=news&importance='0'&id=" + $(this).attr("value");
            Base.post("/admin/tool/changeImportance.php", data,
                function (res) {
                    if (res && res.code == 1) {

                    } else if (res && res.code == 0) {
                        toastr.error(res.msg);
                        setTimeout(function() {
                            window.location.href = res.url;
                        }, 500);
                    }
                });
            $(this).removeAttr("checked");
            flag=0;
        } else {
            return false;

        }
    }
});


/*竞赛页面*/
function removeContest(contest_id, index){
    indexTr = index;
    $('#hiddenid').val(contest_id);//给会话中的隐藏属性ID赋值
    $('#delcfmModel').modal();
}
function submitRemoveContest(contest_id) {
    var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID

    var data = "table=contest&id=" + id;
    Base.post("/admin/tool/delById.php", data,
        function (res) {
            if (res && res.code == 1) {
                toastr.success(res.msg);
                setTimeout(function() {
                    $("#bootstrap-data-table").children('tbody').children('tr').eq(indexTr).remove();
                }, 500);
            } else if (res && res.code == 0) {
                toastr.error(res.msg);
                setTimeout(function() {
                    window.location.href = res.url;
                }, 500);
            }
        });
    return false;
}


/*问题页面*/
function editProblem(problem_id) {
    $('#hiddenid').val(problem_id);//给会话中的隐藏属性ID赋值
    $('#editcfmModel').modal();
}
function submitEditProblem() {
    var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID
    window.location.href="/admin/editproblem.php?id="+ id;

}
function removeProblem(problem_id, index){
    indexTr = index;

    $('#hiddenid').val(problem_id);//给会话中的隐藏属性ID赋值
    $('#delcfmModel').modal();
}
function submitRemoveProblem(contest_id) {
    var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID
    var data = "id=" + id;
    Base.post("/admin/tool/delProblemById.php", data,
        function (res) {
            if (res && res.code == 1) {
                toastr.success(res.msg);
                setTimeout(function() {
                    $("#bootstrap-data-table").children('tbody').children('tr').eq(indexTr).remove();
                }, 500);
            } else if (res && res.code == 0) {
                toastr.error(res.msg);
                setTimeout(function() {
                    window.location.href = res.url;
                }, 500);
            }
        });
    return false;
}

/*权限页面*/
function removePrivilege(id,index){
    indexTr=index;
    $('#hiddenid').val(id);//给会话中的隐藏属性ID赋值
    $('#delcfmModel').modal();
}
function submitRemovePrivilege(id) {
    var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID
    var that = this;
    var data = "table=privilege&id=" + id;
    Base.post("/admin/tool/delById.php", data,
        function (res) {
            if (res && res.code == 1) {
                toastr.success(res.msg);
                setTimeout(function() {
                    $("#bootstrap-data-table").children('tbody').children('tr').eq(indexTr).remove();
                }, 500);
            } else if (res && res.code == 0) {
                toastr.error(res.msg);
                setTimeout(function() {
                    window.location.href = res.url;
                }, 500);
            }
        });
    return false;
}
