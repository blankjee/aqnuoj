<?php
    require_once('../includes/config.inc.php');
    require_once("../includes/my_func.inc.php");
    isLogined();
    isAdministor();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>增加权限 - AQNUOJ后台系统</title>

    <!-- ================= Favicon ================== -->

    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>

    <link href="../static/libs/unix/unix.css" rel="stylesheet">
    <link href="../static/self/css/admin.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../static/libs/toastr/toastr.min.css"/>

    <!--IE -->
    <script language="javascript" type="text/javascript" src="../static/self/js/html5shiv.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/self/js/respond.min.js"></script>
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
                            <h1>添加权限</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"></a>权限管理</li>
                                <li class="active">添加权限</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->

            <section id="main-content horizontal-form-elements">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>对用户增加一个权限</h4>
                        </div>
                        <form id="addPrivilege-form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>用户名</label>
                                            <input name="user_id" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>权限</label>
                                            <select class="form-control bg-ash border-none" name="rightstr">
                                                <option value="">选择一个权限...</option>
                                                <option value="administrator">administrator</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2">
                                    <button name="submit" class="btn btn-default bg-warning border-none" type="submit">保存</button>
                                    <input class="btn btn-default sbmt-btn" type="reset" value="重置">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <!-- Footer START -->
            <?php include('partials/footer.php'); ?>
            <!-- Footer END -->
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


<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script type="text/javascript">
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#addPrivilege-form").submit(function(){
        Base.post("/admin/tool/addPrivilege.php", $('#addPrivilege-form').serialize(),
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
    })
</script>

</body>

</html>
