<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();

    $sql = "SELECT user_id, nick, school, email, create_time FROM users ORDER BY 'reg_time' DESC";
    $result = pdo_query($sql);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>用户管理 - AQNUOJ后台系统</title>
    <!-- ================= Favicon ================== -->
    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../static/libs/toastr/toastr.min.css"/>
    <link href="../static/libs/unix/unix.css" rel="stylesheet">
    <link href="../static/self/css/admin.css" rel="stylesheet">
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
                            <h1>用户管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">用户管理</a></li>
                                <li class="active">用户信息列表</li>
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
                                            <th class="col-md-2">用户ID</th>
                                            <th class="col-md-2">昵称</th>
                                            <th class="col-md-2">学校</th>
                                            <th class="col-md-2">邮箱</th>
                                            <th class="col-md-2">注册时间</th>
                                            <th class="col-md-1">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($result as $rows){
                                            ?>
                                            <tr>
                                                <td><?php echo $rows['user_id']; ?></td>
                                                <td><?php echo $rows['nick']; ?></td>
                                                <td><?php echo $rows['school']; ?></td>
                                                <td><?php echo $rows['email']; ?></td>
                                                <td><?php echo $rows['create_time']; ?></td>
                                                <td>
                                                    <span><a onclick="edit()"><i class="ti-pencil-alt color-success"></i></a></span>
 <span><a onclick="resetPwd('<?php echo $rows['user_id'];?>')"><i class="ti-key color-warning"></i></a></span>
                                                    
<span><a onclick="remove('<?php echo $rows['user_id'];?>')"><i class="ti-trash color-danger"></i> </a></span>
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
                <p>这将删除此用户的所有数据，但不包括其创建的问题&竞赛&实验&公告！您确认要删除吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a  onclick="submitRemove()" class="btn btn-danger" data-dismiss="modal">确定</a>
            </div>
        </div>
    </div>
</div>

<!-- 密码重置确认 -->
<div class="modal fade" id="resetPwdModel">
    <div class="modal-dialog">
        <div class="modal-content message_align">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <p>这将重置此用户的密码为初始密码（oj123456）！您确认要重置吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid2"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a  onclick="submitResetPwd()" class="btn btn-danger" data-dismiss="modal">确定</a>
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


<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>

<!--删除用户-->
<script type="text/javascript">
    function remove(user_id){
        $('#hiddenid').val(user_id);//给会话中的隐藏属性ID赋值
        $('#delcfmModel').modal();
    }
    function submitRemove(contest_id) {
        var id=$.trim($("#hiddenid").val());//获取会话中的隐藏属性ID

        //进行异步删除，并用toaststr提示。
        toastr.options = {
            "positionClass": "toast-top-right",//弹出窗的位置
            "timeOut": "1000",
            "progressBar": "true",
        };

        var data = "table=user&id=" + id;
        Base.post("/admin/tool/delById.php", data,
            function (res) {
                if (res && res.code == 1) {
                    toastr.success(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                } else if (res && res.code == 0) {
                    toastr.error(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                }
            });
        return false;
    }


 function resetPwd(user_id) {
        $('#hiddenid2').val(user_id);//给会话中的隐藏属性ID赋值
        $('#resetPwdModel').modal();
    }

    function submitResetPwd() {
        var id=$.trim($("#hiddenid2").val());//获取会话中的隐藏属性ID

        //进行异步删除，并用toaststr提示。
        toastr.options = {
            "positionClass": "toast-top-right",//弹出窗的位置
            "timeOut": "1000",
            "progressBar": "true",
        };
        var data = "id=" + id;
        Base.post("/admin/tool/resetPwdById.php", data,
            function (res) {
                if (res && res.code == 1) {
                    toastr.success(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                } else if (res && res.code == 0) {
                    toastr.error(res.msg);
                    setTimeout(function() {
                        window.location.href = res.url;
                    }, 1000);
                }
            });
        return false;
    }


</script>
</body>

</html>
