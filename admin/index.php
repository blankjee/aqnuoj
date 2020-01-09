<?php
    require_once('../includes/config.inc.php');
    require_once('../includes/my_func.inc.php');
    isLogined();
    isAdministor();

    /*统计数据*/

    //用户数
    $sql = "SELECT COUNT(*) FROM users";
    $result = pdo_query($sql);
    $nums_user = $result[0][0];

    //问题数
    $sql = "SELECT COUNT(*) FROM problem";
    $result = pdo_query($sql);
    $nums_problem = $result[0][0];

    //竞赛数
    $sql = "SELECT COUNT(*) FROM contest";
    $result = pdo_query($sql);
    $nums_contest = $result[0][0];

    //提交次数
    $sql = "SELECT COUNT(*) FROM solution";
    $result = pdo_query($sql);
    $nums_solution = $result[0][0];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AQNUOJ - 管理后台</title>
    <!-- ================= Favicon ================== -->
    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
                            <h1>Hello, <span>欢迎使用 AQNUOJ 后台管理系统！</span></h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="active">主页</li>
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

                        <div class="col-lg-6">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-primary">
                                        <i class="ti-user"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-text">系统用户数</div>
                                        <div class="stat-digit"><?php echo $nums_user;?></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-success">
                                        <i class="ti-shield"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-text">问题数</div>
                                        <div class="stat-digit"><?php echo $nums_problem;?></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-warning">
                                        <i class="ti-envelope"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-text">竞赛数</div>
                                        <div class="stat-digit"><?php echo $nums_contest;?></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card p-0">
                                <div class="stat-widget-three">
                                    <div class="stat-icon bg-danger">
                                        <i class="ti-face-smile"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-text">总提交数</div>
                                        <div class="stat-digit"><?php echo $nums_solution;?></div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
<!-- Footer START -->
<?php include('partials/footer.php'); ?>
<!-- Footer END -->



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
</body>

</html>