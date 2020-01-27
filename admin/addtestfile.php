<?php
require_once('../includes/config.inc.php');
require_once("../includes/my_func.inc.php");

isLogined();
isAdministor();
authPageContr();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>添加问题 - AQNUOJ后台系统</title>

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

    <!--webuploader-->
    <link rel="stylesheet" type="text/css" href="../static/libs/webuploader/webuploader.css">

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
                            <h1>添加问题</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"></a>问题管理</li>
                                <li class="active">添加问题</li>
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
                                <ul>
                                    <li class="card-option drop-menu">
                                        <ul class="card-option-dropdown dropdown-menu">
                                            <li><a href="#"><i class="ti-loop"></i> Update data</a></li>
                                            <li><a href="#"><i class="ti-menu-alt"></i> Detail log</a></li>
                                            <li><a href="#"><i class="ti-pulse"></i> Statistics</a></li>
                                            <li><a href="#"><i class="ti-power-off"></i> Clear ist</a></li>
                                        </ul>
                                    </li>
                                    <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <div id="uploader" class="wu-example">
                            <!--用来存放文件信息-->
                            <div id="thelist" class="uploader-list"></div>
                            <div class="btns">
                                <div id="picker">选择文件</div>
                                <button id="ctlBtn" class="btn btn-default">开始上传</button>
                            </div>
                        </div>

                        <form id="addProblem-form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>标题</label>
                                            <input name="title" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>时间限制</label>
                                            <input name="time_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>内存限制</label>
                                            <input name="memory_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6"><br/>
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>来源</label>
                                            <input name="source" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
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
<!-- bootstrap -->
<script src="../static/self/js/admin.js"></script>


<script type="text/javascript" src="../static/libs/wangEditor/wangEditor.min.js"></script>

<script type="text/javascript" src="../static/libs/webuploader/webuploader.js"/>

<script>

    var uploader = WebUploader.create({

        // swf文件路径
        swf: '../static/libs/webuploader/Uploader.swf',

        // 文件接收服务端。
        server: 'http://webuploader.duapp.com/server/fileupload.php',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#picker',

        // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
        resize: false
    });

    uploader.on( 'fileQueued', function( file ) {
        $list.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
            '</div>' );
    });

    uploader.on( 'fileQueued', function( file ) {
        $list.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
            '</div>' );
    });

    uploader.on( 'uploadSuccess', function( file ) {
        $( '#'+file.id ).find('p.state').text('已上传');
    });

    uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错');
    });

    uploader.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').fadeOut();
    });

</script>

<script type="text/javascript">
    var E = window.wangEditor
    var descEditor = new E('#descEditor')
    descEditor.create()

    var inputEditor = new E('#inputEditor')
    inputEditor.create()

    var outputEditor = new E('#outputEditor')
    outputEditor.create()

    var sampleInputEditor = new E('#sampleInputEditor')
    sampleInputEditor.create()

    var sampleOutputEditor = new E('#sampleOutputEditor')
    sampleOutputEditor.create()

    var hintEditor = new E('#hintEditor')
    hintEditor.create()
</script>

<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#addProblem-form").submit(function(){
        var data = $('#addProblem-form').serialize() + '&description=' + descEditor.txt.html() + '&input=' + inputEditor.txt.html()
            + '&output=' + outputEditor.txt.html() + '&sampleInput=' + sampleInputEditor.txt.html() + '&sampleOutput=' + sampleOutputEditor.txt.html()
            + '&hint=' + hintEditor.txt.html();
        Base.post("/admin/tool/addProblem.php", data,
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