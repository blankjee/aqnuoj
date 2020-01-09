<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
    if (isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT * FROM news WHERE news_id = '$id'";
        $result = pdo_query($sql);
        $rows = $result[0];
    }else{
        echo "无此公告";
        exit;
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .w-e-text:hover{
            background:#fff!important;
        }
        .w-e-text:focus{
            background:#fff!important;
        }
    </style>
    <title>修改公告 - AQNUOJ后台系统</title>

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
                            <h1>修改公告</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"></a>公告管理</li>
                                <li class="active">修改公告</li>
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

                        <form id="editNews-form" method="POST">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>标题</label>
                                            <input id="title" name="title" type="text" class="form-control border-none input-flat bg-ash" placeholder="" value="<?php echo $rows['title'];?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>正文内容</label>
                                    <div id="contentEditor">

                                    </div>
                                </div>

                            </div>
                            <br/>
                            <button class="btn btn-default bg-warning border-none" type="submit">更新</button>
                            <input class="btn btn-default sbmt-btn" type="reset" value="重置"/>
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
<!-- bootstrap -->


<script type="text/javascript" src="../static/libs/wangEditor/wangEditor.min.js"></script>


<script type="text/javascript">
    var E = window.wangEditor
    var contentEditor = new E('#contentEditor')
    contentEditor.create()
    contentEditor.txt.html('<?php echo $rows['content'];?>')
</script>

<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#editNews-form").submit(function(){
	var title = $('#title').val();
	var content = contentEditor.txt.html();
	var id = <?php echo $id;?>;	
        var data = {
		'title' : title,
		'content': content,
		'id' : id
	};
        Base.post("/admin/tool/editNews.php", data,
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
