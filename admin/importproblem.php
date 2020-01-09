<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
    function writable($path){
        $ret=false;
        $fp=fopen($path."/testifwritable.tst","w");
        $ret=!($fp===false);
        fclose($fp);
        unlink($path."/testifwritable.tst");
        return $ret;
    }
   $maxfile=min(ini_get("upload_max_filesize"),ini_get("post_max_size"));
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
                        <div class="card">
                            <div class="card-body">
                        <p>
                            导入 FPS 数据，请确保您的文件大小小于 [<?php echo $maxfile?>] <br/>
                            如有需要，可以在 PHP.ini中设置 upload_max_filesize 和 post_max_size<br/>
                            如何导入大文件[10M+]，请在 PHP.ini 中增大 [memory_limit]。<br>
                        </p>
                        <?php
                            $show_form=true;
                            if(!isset($OJ_SAE)||!$OJ_SAE){
                               if(!writable($OJ_DATA)){
                                   echo "您应该增加  $OJ_DATA 进您的 PHP.ini 中 open_basedir 设置，或者                                            or you need to execute:<br>
                                               <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br><br>";
                                    $show_form=false;
                               }
                               if(!file_exists("../upload"))mkdir("../upload");
                               if(!writable("../upload")){

                                   echo "../upload 不可写，请 <b>chmod 770</b><br>";
                                   $show_form=false;
                               }
                            }
                            if($show_form){
                        ?>
                            <br>
                            <form action='problem_import_xml.php' method='post' enctype="multipart/form-data">
                                <p>导入问题：</p>
                                <input type="file" name="fps"><br/>
                                <div class="input-append">
                                    <input class="btn btn-primary" type=submit value="上传" style="height:30px;">
                                </div>

                            </form>
                            <?php

                                }

                        ?>
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
