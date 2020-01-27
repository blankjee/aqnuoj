<?php
    require_once('../includes/config.inc.php');
    require_once("../includes/my_func.inc.php");

    isLogined();
    isAdministor();
    //权限控制
    authPageContr();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>添加竞赛&作业 - AQNUOJ后台系统</title>

    <!-- ================= Favicon ================== -->

    <!-- Styles -->
    <link href="../static/libs/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="../static/libs/themify/themify-icons.css" rel="stylesheet">
    <link href="../static/libs/menubar/sidebar.css" rel="stylesheet">
    <link href="../static/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script language="javascript" type="text/javascript" src="../static/libs/jquery/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
    <!-- 时间控件-->
    <link href="../static/libs/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

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
                            <h1>添加竞赛&作业</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"></a>竞赛&作业管理</li>
                                <li class="active">添加竞赛&作业</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->

            <section id="main-content">
                <div class="card alert">
                    <div class="card-body">
                        <div class="card-header m-b-20">
                            <h4>&nbsp;</h4>
                        </div>

                        <form id="addContest-form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>标题</label>
                                            <input name="title" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>类型</label>
                                            <select name="cat" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                                <option value="0">竞赛</option>
                                                <option value="1">作业</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>开始时间</label>
                                            <input name="start_time" type="text" class="form-control calendar bg-ash" placeholder="" id="start_time_picker">
                                            <span class="ti-calendar form-control-feedback booking-system-feedback m-t-30"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>结束时间</label>
                                            <input name="end_time" type="text" class="form-control bg-ash" placeholder="" id="end_time_picker">
                                            <span class="ti-calendar form-control-feedback booking-system-feedback m-t-30"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>竞赛描述</label>
                                            <textarea rows="6" name="description" type="text" class="form-control border-none input-flat bg-ash" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>竞赛&作业包含的题目编号（用英文逗号隔开）</label>
                                            <textarea rows="6" name="cproblem" type="text" class="form-control border-none input-flat bg-ash" placeholder="例如：1000,1001"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>选择语言</label>
                                        <select name="lang[]" multiple="" class="form-control" style="height:100px">
                                            <option value="0" selected="">
                                                C
                                            </option><option value="1">
                                                C++
                                            </option><option value="2">
                                                Pascal
                                            </option><option value="3">
                                                Java
                                            </option><option value="6">
                                                Python
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>是否公开</label>
                                        <select class="form-control bg-ash border-none" name="private">
                                            <option value="0">公开</option>
                                            <option value="1">私有</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>设置密码（仅私有时可用）</label>
                                        <input name="password" type="password" class="form-control border-none input-flat bg-ash" placeholder="">
                                    </div>

                                </div>
                            </div>

                            </div>
                            <br/>
                            <button class="btn btn-default bg-warning border-none" type="submit" value="">添加</button>
                            <input class="btn btn-default sbmt-btn" type="reset" value="重置">

                        </form>
                    </div>
                </div>

                <!-- Footer START -->
                <?php include('partials/footer.php'); ?>
                <!-- Footer END -->
            </section>
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
<script src="../static/libs/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="../static/libs/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<!-- bootstrap -->
<script src="../static/self/js/admin.js"></script>


<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#addContest-form").submit(function(){
        Base.post("/admin/tool/addContest.php", $('#addContest-form').serialize(),
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
<script type="text/javascript">
    $('#start_time_picker, #end_time_picker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'zh-CN',
        pickDate: true,
        pickTime: true,
        inputMask: true,
        pickerPosition: "bottom-left",
        autoclose: true,
        laguage:'zh-CN'　
    });

</script>


</body>

</html>
