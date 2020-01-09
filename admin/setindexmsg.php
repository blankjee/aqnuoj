<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
    $msgtitle = file_get_contents("../static/text/msgtitle.txt");
    $msgcontent = file_get_contents("../static/text/msgcontent.txt");
    $msgcontent = str_replace("<br />","\r\n",$msgcontent);
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>设置公告 - AQNUOJ后台系统</title>
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
                            <h1>设置公告</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#">设置</a></li>
                                <li class="active">设置公告</li>
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
                            <div class="card-header-right-icon">
                            </div>
                        </div>
                        <form id="setMsg-form" method='POST'>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>公告标题</label>
                                            <input id="msgtitle" name='msgtitle' class="form-control border-none input-flat bg-ash"  value="<?php echo $msgtitle?>"/><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>公告内容</label>
                                            <textarea id="msgcontent" name='msgcontent' rows="8" class="form-control border-none input-flat bg-ash" ><?php echo $msgcontent?></textarea><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input class="btn btn-default bg-warning border-none" type="submit" value="更新"/>
                            <input class="btn btn-default sbmt-btn" type="reset" value="重置"/>
                        </form>
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



<script src="../static/libs/jquery/jquery.min.js"></script>
<!-- jquery vendor -->
<script src="../static/libs/jquery/jquery.nanoscroller.min.js"></script>
<!-- nano scroller -->
<script src="../static/libs/menubar/sidebar.js"></script>
<script src="../static/libs/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="../static/libs/bootstrap/js/bootstrap.min.js"></script>
<!-- bootstrap -->


<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>
<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#setMsg-form").submit(function(){
        Base.post("/admin/tool/setIndexMsg.php", $('#setMsg-form').serialize(),
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
