<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();

    $sql = "SELECT * FROM contest ORDER BY contest_id DESC";
    $result = pdo_query($sql);



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>竞赛&实验管理 - AQNUOJ后台系统</title>
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
                            <h1>竞赛&实验管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">竞赛&实验管理</a></li>
                                <li class="active">竞赛&实验列表</li>
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
                                            <th class="col-md-1">ID</th>
                                            <th class="col-md-3">标题</th>
                                            <th class="col-md-1">状态</th>
                                            <th class="col-md-2">开始时间</th>
                                            <th class="col-md-2">结束时间</th>
                                            <th class="col-md-2">竞赛结果</th>
                                            <th class="col-md-1">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($result as $k=>$rows){

                                            ?>
                                            <tr>
                                                <td><?php echo $rows['contest_id']; ?></td>
                                                <td><a href="/home/contest.php?id=<?php echo $rows['contest_id'];?>"/> <?php echo $rows['title']; ?></td>
                                                <td><?php echo $rows['private'] == '1' ? '私有' : '公开'; ?></td>
                                                <td><?php echo $rows['start_time']; ?></td>
                                                <td><?php echo $rows['end_time']; ?></td>
                                                <td><a href="<?php echo '/home/contestrank.php?cid=' . $rows['contest_id'];?>">查看</a></td>
                                                <td>
                                                    <span><a href="editcontest.php?id=<?php echo $rows['contest_id'];?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                    <span><a onclick="removeContest(<?php echo $rows['contest_id'];?>,<?php echo $k;?>)"><i class="ti-trash color-danger"></i> </a></span>
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
                <p>这将删除关于竞赛的所有数据！您确认要删除吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a  onclick="submitRemoveContest()" class="btn btn-danger" data-dismiss="modal">确定</a>
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
<script type="text/javascript" src="../static/self/js/function.js"></script>

</body>

</html>
