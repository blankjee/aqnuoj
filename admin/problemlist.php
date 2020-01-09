<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();

//获取当前用户的id
$user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];

/*分页数据*/
//获取当前页数
if (isset($_GET['page'])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

//设置每页最多显示的记录数
$each_page = $PAGE_EACH / 2;

//计算页面的开始位置
if (!$page || $page == 1) {
    $start = 0;
} else {
    $offset = $page - 1;
    $start = ($offset * $each_page);
}

$wd = cleanParameter("get", "wd");

$visitWhere = "visible_by = '' OR visible_by is NULL OR visible_by = '".$user_id."' ";

if (!$wd) {
    //无搜索内容
    //获取总页数
    $sql = "SELECT COUNT(1) FROM problem WHERE ".$visitWhere;
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    //var_dump($total_num);exit;
    $total_page = ceil($total_num / $each_page);
    //查询问题列表
    $sql = "SELECT problem_id, title, accepted, create_time, defunct FROM problem WHERE ".$visitWhere . " ORDER BY problem_id ASC LIMIT $start, $each_page";
    $result = pdo_query($sql);
} else {
    //有搜索内容
    $sql = "SELECT COUNT(1) FROM problem WHERE (title LIKE '%$wd%' OR problem_id LIKE '%$wd%') AND (" . $visitWhere . ")";
    $result = pdo_query($sql);
    $total_num = (int)$result[0][0];
    $total_page = ceil($total_num / $each_page);
    //查询问题列表
    $sql = "SELECT problem_id, title, accepted, create_time, defunct FROM problem WHERE (title LIKE '%$wd%' OR problem_id LIKE '%$wd%') AND (" . $visitWhere.") ORDER BY problem_id ASC LIMIT $start, $each_page";
    $result = pdo_query($sql);
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>问题管理 - AQNUOJ后台系统</title>
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
                            <h1>问题管理</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">问题管理</a></li>
                                <li class="active">问题列表</li>
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

                                <form action="/admin/problemlist.php" method="get">
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-search"></i></span>
                                                    <input class="form-control" type="text" placeholder="输入搜索内容..."
                                                           name="wd">
                                                </div>
                                            </td>
                                            <td>
                                                <input class="btn btn-primary btn-sm" type="submit" value="搜索"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                            </div>

                            <div class="bootstrap-data-table-panel">
                                <div class="table-responsive">
                                    <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="col-md-1">问题ID</th>
                                            <th class="col-md-3">标题</th>
                                            <th class="col-md-1">AC数</th>
                                            <th class="col-md-2">更新时间</th>
                                            <th class="col-md-1">是否屏蔽</th>
                                            <th class="col-md-1">测试数据</th>
                                            <th class="col-md-1">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($result as $k => $rows) {

                                            ?>
                                            <tr>
                                                <td><?php echo $rows['problem_id']; ?></td>
                                                <td>
                                                    <a href="/home/problem.php?id=<?php echo $rows['problem_id']; ?>"/> <?php echo $rows['title']; ?>
                                                </td>
                                                <td><?php echo $rows['accepted']; ?></td>
                                                <td><?php echo $rows['create_time']; ?></td>
                                                <td>
                                                    <div class="material-switch pull-right">
                                                        <input class="chk_pr"
                                                               id="SwitchOptionDanger-<?php echo $rows['problem_id']; ?>"
                                                               name="someSwitchOption001" <?php echo $rows['defunct'] === 'Y' ? "checked='true'" : ""; ?>
                                                               type="checkbox"
                                                               value="<?php echo $rows['problem_id']; ?>"/>
                                                        <label for="SwitchOptionDanger-<?php echo $rows['problem_id']; ?>"
                                                               class="label-danger"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="/admin/phpfm.php?frame=3&pid=<?php echo $rows['problem_id']; ?>">测试数据</a>
                                                </td>
                                                <td>
                                                     <span><a onclick="editProblem(<?php echo $rows['problem_id']; ?>)"><i class="ti-pencil-alt color-success"></i></a></span>
                                                    <span><a onclick="removeProblem(<?php echo $rows['problem_id']; ?>,<?php echo $k; ?>)"><i
                                                                    class="ti-trash color-danger"></i> </a></span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <!-- 页码样式 -->
                                    <div class="col-sm-12">
                                        <div class="dataTables_paginate paging_simple_numbers" id="">
                                            <ul class="pagination">
                                                <?php if ($page == 1) { ?>
                                                    <li class="paginate_button previous disabled"
                                                        id="bootstrap-data-table_previous"><a
                                                                href="javascript:return false;"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="0" tabindex="0">首页</a>
                                                    </li>
                                                    <li class="paginate_button previous disabled"
                                                        id="bootstrap-data-table_previous"><a
                                                                href="javascript:return false;"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $page - 1; ?>"
                                                                tabindex="0">上一页</a>
                                                    </li>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <li class="paginate_button previous"
                                                        id="bootstrap-data-table_previous"><a
                                                                href="/admin/problemlist.php?wd=<?php echo $wd;?>&page=1"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="0" tabindex="0">首页</a>
                                                    </li>
                                                    <li class="paginate_button previous"
                                                        id="bootstrap-data-table_previous"><a
                                                                href="/admin/problemlist.php?wd=<?php echo $wd;?>&page=<?php echo $page - 1; ?>"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $page - 1; ?>"
                                                                tabindex="0">上一页</a>
                                                    </li>
                                                <?php } ?>
                                                <?php for ($i = 1; $i <= $total_page; $i++) {
                                                    ?>
                                                    <li <?php if ($page == $i) { ?> class="paginate_button active"<?php } ?>>
                                                        <a href="/admin/problemlist.php?wd=<?php echo $wd;?>&page=<?php echo $i; ?>"
                                                           aria-controls="bootstrap-data-table"
                                                           data-dt-idx="<?php echo $i; ?>"
                                                           tabindex="0"><?php echo $i; ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <?php if ($page == $total_page) { ?>
                                                    <li class="paginate_button next disabled"
                                                        id="bootstrap-data-table_next"><a
                                                                href="javascript:return false;"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $page + 1; ?>"
                                                                tabindex="0">下一页</a></li>
                                                    <li class="paginate_button next disabled"
                                                        id="bootstrap-data-table_next"><a
                                                                href="javascript:return false;"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $total_page; ?>"
                                                                tabindex="0">尾页</a></li>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <li class="paginate_button next" id="bootstrap-data-table_next"><a
                                                                href="/admin/problemlist.php?wd=<?php echo $wd;?>&page=<?php echo $page + 1; ?>"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $page + 1; ?>"
                                                                tabindex="0">下一页</a></li>
                                                    <li class="paginate_button next" id="bootstrap-data-table_next"><a
                                                                href="/admin/problemlist.php?wd=<?php echo $wd;?>&page=<?php echo $total_page; ?>"
                                                                aria-controls="bootstrap-data-table"
                                                                data-dt-idx="<?php echo $total_page; ?>"
                                                                tabindex="0">尾页</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>


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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <p>这将删除关于问题的所有数据！您确认要删除吗？</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a onclick="submitRemoveProblem()" class="btn btn-danger" data-dismiss="modal">确定</a>
            </div>
        </div>
    </div>
</div>

<!-- 信息修改确认 -->
<div class="modal fade" id="editcfmModel">
    <div class="modal-dialog">
        <div class="modal-content message_align">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title">提示信息</h4>
            </div>
            <div class="modal-body">
                <p>该编辑功能只是体验功能，请谨慎编辑！非必要情况请勿使用！</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="hiddenid"/>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <a onclick="submitEditProblem()" class="btn btn-danger" data-dismiss="modal">确定</a>
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
<!--<script src="../static/self/js/admin.js"></script>-->


<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/self/js/function.js"></script>


</body>

</html>
