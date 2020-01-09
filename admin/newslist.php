<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();

$sql = "SELECT * FROM news ORDER BY importance DESC, create_time DESC ";
$result = pdo_query($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>公告管理 - AQNUOJ后台系统</title>
    <!-- ================= Favicon ================== -->
    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../static/libs/toastr/toastr.min.css"/>
    <link href="../static/libs/unix/unix.css" rel="stylesheet">
    <link href="../static/self/css/admin.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/switch.css" rel="stylesheet">

</head>

<body>

<!-- SideBar START -->
<?php include('partials/sidebar.php'); ?>
<!-- SideBar END -->

<!-- Header START -->
<?php include('partials/header.php'); ?>
<!-- Header END -->

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>公告管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">公告管理</a></li>
                                <li class="active">公告列表</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <section id="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card alert">
                            <div class="card-header">
                                <h4>&nbsp;</h4>

                            </div>
                            <div class="bootstrap-data-table-panel">
                                <div class="table-responsive">
                                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="col-md-4">标题</th>
                                            <th class="col-md-4">创建时间</th>
                                            <th class="col-lg-1">是否置顶</th>
                                            <th class="col-lg-1">是否屏蔽</th>
                                            <th class="col-md-1">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($result as $k=>$rows){

                                            ?>
                                            <tr>
                                                <td hidden=""><?php echo $rows['news_id']; ?></td>
                                                <td><a href="/home/newsdetail.php?id=<?php echo $rows['news_id'];?>"/> <?php echo $rows['title']; ?></td>
                                                <td><?php echo $rows['create_time']; ?></td>
                                                <td>
                                                    <div class="material-switch pull-right">
                                                        <input class="chk_im" id="SwitchOptionPrimary-<?php echo $rows['news_id'];?>" name="someSwitchOption001" <?php echo $rows['importance'] === '1' ? "checked='true'" : "";?> type="checkbox" value="<?php echo $rows['news_id'];?>"/>
                                                        <label for="SwitchOptionPrimary-<?php echo $rows['news_id'];?>" class="label-primary"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="material-switch pull-right">
                                                        <input class="chk_de" id="SwitchOptionDanger-<?php echo $rows['news_id'];?>" name="someSwitchOption001" <?php echo $rows['defunct'] === 'Y' ? "checked='true'" : "";?> type="checkbox" value="<?php echo $rows['news_id'];?>"/>
                                                        <label for="SwitchOptionDanger-<?php echo $rows['news_id'];?>" class="label-danger"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span><a href="editnews.php?id=<?php echo $rows['news_id'];?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                    <span><a onclick="removeNews(<?php echo $rows['news_id'];?>,<?php echo $k;?>)"><i class="ti-trash color-danger"></i> </a></span>
                                                </td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <!-- Footer START -->
                <?php include('partials/footer.php'); ?>
                <!-- Footer END -->
            </section>
        </div>
    </div>
</div>


</div>

<!-- 信息删除确认 -->
<div class="modal fade" id="delcfmModel">
    <div class="modal-dialog">
        <div class="modal-content message_align">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <p>您确认要删除吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a  onclick="submitRemoveNews()" class="btn btn-danger" data-dismiss="modal">确定</a>
            </div>
        </div>
    </div>
</div>

<script src="../static/libs/jquery/jquery.min.js"></script>
<!-- jquery vendor -->
<script src="../static/libs/jquery/jquery.nanoscroller.min.js"></script>
<!-- nano scroller -->
<script src="../static/libs/menubar/sidebar.js"></script>
<script src="../static/libs/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap -->
<script src="../static/self/js/admin.js"></script>
<script src="../static/libs/data-table/datatables.min.js"></script>
<script src="../static/libs/data-table/datatables-init.js"></script>

<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/function.js"></script>

<!--删除竞赛-->
<!--<script type="text/javascript">-->
<!--    function remove(news_id){-->
<!--        $('#hiddenid').val(news_id);//给会话中的隐藏属性ID赋值-->
<!--        $('#delcfmModel').modal();-->
<!--    }-->
<!--    function submitRemove(contest_id) {-->
<!--        var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID-->
<!---->
<!--        //进行异步删除，并用toaststr提示。-->
<!--        toastr.options = {-->
<!--            "positionClass": "toast-top-right",//弹出窗的位置-->
<!--            "timeOut": "1000",-->
<!--            "progressBar": "true",-->
<!--        };-->
<!---->
<!--        var data = "table=news&id=" + id;-->
<!--        Base.post("/admin/tool/delById.php", data,-->
<!--            function (res) {-->
<!--                if (res && res.code == 1) {-->
<!--                    toastr.success(res.msg);-->
<!--                    setTimeout(function() {-->
<!--                        window.location.href = res.url;-->
<!--                    }, 1000);-->
<!--                } else if (res && res.code == 0) {-->
<!--                    toastr.error(res.msg);-->
<!--                    setTimeout(function() {-->
<!--                        window.location.href = res.url;-->
<!--                    }, 1000);-->
<!--                }-->
<!--            });-->
<!--        return false;-->
<!--    }-->
<!---->
<!--</script>-->
<!---->
<!--<script type="text/javascript">-->
<!--    toastr.options = {-->
<!--        "positionClass": "toast-top-right",//弹出窗的位置-->
<!--        "timeOut": "500",-->
<!--        "progressBar": "true",-->
<!--    };-->
<!--</script>-->
<!---->
<!--<script>-->
<!--    $('body').on('click','.chk_de',function(event) {-->
<!--        var flag;-->
<!--        if(this.checked) {-->
<!--            var stt=confirm("确定要屏蔽吗？");-->
<!--            if(stt) {-->
<!--                //修改数据库中的Defunct=Y-->
<!--                var data = "table=news&defunct='Y'&id=" + $(this).attr("value");-->
<!--                Base.post("/admin/tool/changeDefunct.php", data,-->
<!--                    function (res) {-->
<!--                        if (res && res.code == 1) {-->
<!---->
<!--                        } else if (res && res.code == 0) {-->
<!--                            toastr.error(res.msg);-->
<!--                            setTimeout(function() {-->
<!--                                window.location.href = res.url;-->
<!--                            }, 500);-->
<!--                        }-->
<!--                    });-->
<!--                $(this).attr("checked",true);-->
<!--                flag=1;-->
<!--            } else {-->
<!--                $(this).removeAttr("checked");-->
<!--            }-->
<!--        } else {-->
<!--            var stt=confirm("确定要取消屏蔽吗？");-->
<!--            if(stt) {-->
<!--                //修改数据库中的Defunct=N-->
<!--                var data = "table=news&defunct='N'&id=" + $(this).attr("value");-->
<!--                Base.post("/admin/tool/changeDefunct.php", data,-->
<!--                    function (res) {-->
<!--                        if (res && res.code == 1) {-->
<!---->
<!--                        } else if (res && res.code == 0) {-->
<!--                            toastr.error(res.msg);-->
<!--                            setTimeout(function() {-->
<!--                                window.location.href = res.url;-->
<!--                            }, 500);-->
<!--                        }-->
<!--                    });-->
<!--                $(this).removeAttr("checked");-->
<!--                flag=0;-->
<!--            } else {-->
<!--                return false;-->
<!--            }-->
<!--        }-->
<!--    });-->
<!---->
<!--    $('body').on('click','.chk_im',function(event) {-->
<!--        var flag;-->
<!--        if(this.checked) {-->
<!--            var stt=confirm("确定要置顶吗？");-->
<!--            if(stt) {-->
<!--                //修改数据库中的importance=1-->
<!--                var data = "table=news&importance='1'&id=" + $(this).attr("value");-->
<!--                Base.post("/admin/tool/changeImportance.php", data,-->
<!--                    function (res) {-->
<!--                        if (res && res.code == 1) {-->
<!---->
<!--                        } else if (res && res.code == 0) {-->
<!--                            toastr.error(res.msg);-->
<!--                            setTimeout(function() {-->
<!--                                window.location.href = res.url;-->
<!--                            }, 500);-->
<!--                        }-->
<!--                    });-->
<!--                $(this).attr("checked",true);-->
<!--                flag=1;-->
<!--            } else {-->
<!--                $(this).removeAttr("checked");-->
<!--            }-->
<!--        } else {-->
<!--            var stt=confirm("确定要取消置顶吗？");-->
<!--            if(stt) {-->
<!--                //修改数据库中的importance=0-->
<!--                var data = "table=news&importance='0'&id=" + $(this).attr("value");-->
<!--                Base.post("/admin/tool/changeImportance.php", data,-->
<!--                    function (res) {-->
<!--                        if (res && res.code == 1) {-->
<!---->
<!--                        } else if (res && res.code == 0) {-->
<!--                            toastr.error(res.msg);-->
<!--                            setTimeout(function() {-->
<!--                                window.location.href = res.url;-->
<!--                            }, 500);-->
<!--                        }-->
<!--                    });-->
<!--                $(this).removeAttr("checked");-->
<!--                flag=0;-->
<!--            } else {-->
<!--                return false;-->
<!---->
<!--            }-->
<!--        }-->
<!--    });-->
<!--</script>-->

<!-- scripit init-->

</body>

</html>