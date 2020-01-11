<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
$sql = "SELECT contest_id, title FROM contest";
$contests = pdo_query($sql);

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
                            <h1>竞赛管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">竞赛管理</a></li>
                                <li class="active">导出学生竞赛代码</li>
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
                        <div class="card">
                            <div class="card-body">
                                <p>
                                    选择正确的竞赛ID，点击导出按钮后，程序会自动执行，如果竞赛的提交量大，等待时间会长。使用该功能请在系统空闲时间使用。<br/>
                                    <span style="color: red">点击[开始导出]后请不要进行其他操作，数据量大请耐心等待。</span>
                                </p>
                                    <br>
                                <h3>选择竞赛</h3>
                                <form id="export_stucode" action="tool/exportStuCode.php" method="post">
                                    <select name="contest">
                                        <?php
                                        foreach ($contests as $contest){?>
                                            <option value="<?php echo $contest['contest_id'];?>"><?php echo $contest['contest_id'] . " - " . $contest['title'];?></option>
                                        <?php }?>
                                    </select>
                                    <input type="submit" name="submit" value="开始导出" />
                                </form>


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

</body>
</html>
