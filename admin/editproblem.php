<?php
    require_once('../includes/config.inc.php');
    require_once("../includes/my_func.inc.php");

    isLogined();
    isAdministor();

    if (!isset($_GET['id'])){
        echo "<script>alert('无此问题！');location.href='/admin/problemlist.php';</script>";
        exit;
    }else{
        $id = $_GET['id'];
        //查询是否存在此ID的问题
        $sql = "SELECT * from problem WHERE problem_id = ?";
        $result = pdo_query($sql, $id);
        $nums = count($result);
        if ($nums != 1){
            echo "<script>alert('问题不存在！');location.href='/admin/problemlist.php';</script>";
            exit;
        }else{
            $problemInfo  = $result[0];
            //处理数据格式
            //$problemInfo['description'] = htmlentities($problemInfo['description']);
            //var_dump($problemInfo['description']);exit;
            //$problemInfo['description'] = str_replace("br", "\n", $problemInfo['description']);
            $problemInfo['description'] = str_replace("<br>", "\n", $problemInfo['description']);
            $problemInfo['description'] = str_replace("<br />", "\n", $problemInfo['description']);
            $problemInfo['input'] = str_replace("<br>", "\n", $problemInfo['input']);
            $problemInfo['input'] = str_replace("<br />", "\n", $problemInfo['input']);
            $problemInfo['output'] = str_replace("<br>", "\n", $problemInfo['output']);
            $problemInfo['output'] = str_replace("<br />", "\n", $problemInfo['output']);
            $problemInfo['sample_input'] = str_replace("<br>", "\n", $problemInfo['sample_input']);
            $problemInfo['sample_input'] = str_replace("<br />", "\n", $problemInfo['sample_input']);
            $problemInfo['sample_output'] = str_replace("<br>", "\n", $problemInfo['sample_output']);
            $problemInfo['sample_output'] = str_replace("<br />", "\n", $problemInfo['sample_output']);
            $problemInfo['hint'] = str_replace("<br>", "\n", $problemInfo['hint']);
            $problemInfo['hint'] = str_replace("<br />", "\n", $problemInfo['hint']);
        }
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
    <title>修改问题 - AQNUOJ后台系统</title>

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
                            <h1>修改问题</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="#"></a>问题管理</li>
                                <li class="active">修改问题</li>
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

                        <form id="editProblem-form" method="POST" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>标题</label>
                                            <input name="title" type="text" class="form-control border-none input-flat bg-ash" placeholder="" value="<?php echo $problemInfo['title'];?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>时间限制</label>
                                            <input name="time_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="" value="<?php echo $problemInfo['time_limit'];?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>内存限制</label>
                                            <input name="memory_limit" type="text" class="form-control border-none input-flat bg-ash" placeholder="" value="<?php echo $problemInfo['memory_limit'];?>">
                                        </div>
                                    </div>
                                </div>
				<div class="col-md-4">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>是否仅创建者可见<br>(如果需要仅创建者可见请选“是”，反之选“否”)</label>
                                            <div>
						<?php
						if ($problemInfo['visible_by'] != null || $problemInfo['visible_by'] != ''){?>
						<label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_yes" value="1" checked> 是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_no"  value="0"> 否
                                                </label>

						<?php }else{?>
                                                <label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_yes" value="1"> 是
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="visible" id="Radios_no"  value="0" checked> 否
                                                </label>
						<?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>题目描述</label>
                                    <textarea class="form-control" rows="8" name="description" id="descEditor"><?php echo $problemInfo['description'];?></textarea>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>输入</label>
                                    <textarea class="form-control" rows="5" name="input" id="inputEditor"><?php echo $problemInfo['input'];?></textarea>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>输出</label>
                                    <textarea class="form-control" rows="5" name="output" id="outputEditor"><?php echo $problemInfo['output'];?></textarea>
                                </div>
                                <div class="col-md-12"><br/>
                                    <label>样例输入</label>
                                    <textarea class="form-control" rows="5" name="sample_input" id="sampleInputEditor"><?php echo $problemInfo['sample_input'];?></textarea>

                                </div>
                                <div class="col-md-12"><br/>
                                    <label>样例输出</label>
                                    <textarea class="form-control" rows="5" name="sample_output" id="sampleOutputEditor"><?php echo $problemInfo['sample_output'];?></textarea>

                                </div>
                                <span style="color: red">*请在题目添加完成后录入更多测试数据</span>
                                <div class="col-md-12"><br/>
                                    <label>提示</label>
                                    <textarea class="form-control" rows="3" name="hint" id="hintEditor"><?php echo $problemInfo['hint'];?></textarea>
                                </div>
                                <div class="col-md-6"><br/>
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>来源</label>
                                            <input name="source" type="text" class="form-control border-none input-flat bg-ash" placeholder=""value="<?php echo $problemInfo['hint'];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <button class="btn btn-default bg-warning border-none" type="submit" value="">更新</button>
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



<script type="text/javascript" src="../static/self/js/aqnuoj.js"></script>
<script type="text/javascript" src="../static/libs/toastr/toastr.min.js"></script>

<script>
    toastr.options = {
        "positionClass": "toast-top-right",//弹出窗的位置
        "timeOut": "1000",
        "progressBar": "true",
    };
    $("#editProblem-form").submit(function(){
        //var data = $('#editProblem-form').serialize() + '&description=' + descEditor.txt.html() + '&input=' + inputEditor.txt.html()
        //    + '&output=' + outputEditor.txt.html() + '&sampleInput=' + sampleInputEditor.txt.html() + '&sampleOutput=' + sampleOutputEditor.txt.html()
        //    + '&hint=' + hintEditor.txt.html() + '&id=' + <?php //echo $id;?>//;
        var data = $('#editProblem-form').serialize() + '&id=' + <?php echo $id;?>;
        //alert(data);
        Base.post("/admin/tool/editProblem.php", data,
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
