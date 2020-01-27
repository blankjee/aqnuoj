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
    <style>
        .w-e-text:hover{
            background:#fff!important;
        }
        .w-e-text:focus{
            background:#fff!important;
        }
    </style>
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
                                    <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                                </ul>
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
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>时间限制(单位：s)</label>
                                            <input name="time_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>内存限制(单位：MB)</label>
                                            <input name="memory_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>是否仅创建者可见<br>(如果需要仅创建者可见请选“是”，反之选“否”)</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_yes" value="1"> 是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_no"  value="0" checked> 否
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>题目描述</label>
                                    <div id="descEditor">

                                    </div>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>输入</label>
                                    <div id="inputEditor">

                                    </div>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>输出</label>
                                    <div id="outputEditor">

                                    </div>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>样例输入</label>
                                    <div id="sampleInputEditor">

                                    </div>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>样例输出</label>
                                    <div id="sampleOutputEditor">

                                    </div>
                                </div>
                                <span style="color: red">*请在题目添加完成后录入更多测试数据</span>
                                <div class="col-md-12"><br/>
                                    <label>提示</label>
                                    <div id="hintEditor">

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


<script type="text/javascript">
    var E = window.wangEditor
    var descEditor = new E('#descEditor')
    descEditor.create({
        height:'200px'
    })

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
